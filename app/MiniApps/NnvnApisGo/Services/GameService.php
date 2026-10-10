<?php

namespace App\MiniApps\NnvnApisGo\Services;

use App\MiniApps\NnvnApisGo\Constants;
use App\MiniApps\NnvnApisGo\Exceptions\GameException;
use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Models\Player;
use App\MiniApps\NnvnApisGo\Models\Round;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GameService
{
    private const GIFT_COUNT_CACHE_KEY = 'nnvn:gift_count';
    private const GIFT_COUNT_CACHE_TTL = 30; // seconds

    /**
     * Cached global gift count for READ/display paths (boxes/config, start).
     * The authoritative award decision still re-counts under lock in completeGame/claimGiftApis.
     */
    public static function globalGiftCount(): int
    {
        return Cache::remember(self::GIFT_COUNT_CACHE_KEY, self::GIFT_COUNT_CACHE_TTL, function () {
            return Game::where('gift_apis_found', true)
                ->where('status', 'completed')
                ->count();
        });
    }

    private static function forgetGiftCountCache(): void
    {
        Cache::forget(self::GIFT_COUNT_CACHE_KEY);
    }

    public function registerOrLogin(string $name): Player
    {
        return Player::firstOrCreate(['name' => $name]);
    }

    public function getPlayer(int $id): Player
    {
        return Player::findOrFail($id);
    }

    public function startGame(int $playerId): Game
    {
        $player = Player::findOrFail($playerId);

        if ($player->round_apis_found >= Constants::MAX_APIS) {
            throw GameException::maxApisReached();
        }

        $abandonedGameId = $this->abandonActiveGames($playerId);

        $game = Game::create([
            'player_id' => $playerId,
            'status' => 'playing',
        ]);

        $game->abandoned_game_id = $abandonedGameId;

        // Check gift availability globally (cached read — display only)
        $game->gift_apis_available = self::globalGiftCount() < Constants::MAX_GIFT_APIS_GLOBAL;

        return $game;
    }

    public function submitRound(Game $game, int $roundNumber, float $timeSeconds, bool $quizUsed): Round
    {
        if ($game->status !== 'playing') {
            throw GameException::gameNotActive();
        }

        if ($timeSeconds < Constants::MIN_ROUND_TIME) {
            throw GameException::invalidRoundTime();
        }

        $existingRound = Round::where('game_id', $game->id)
            ->where('round_number', $roundNumber)
            ->first();

        if ($existingRound) {
            throw GameException::roundExists();
        }

        return Round::create([
            'game_id' => $game->id,
            'round_number' => $roundNumber,
            'time_seconds' => $timeSeconds,
            'quiz_used' => $quizUsed,
        ]);
    }

    public function completeGame(Game $game, bool $giftApisFound): array
    {
        if ($game->status !== 'playing') {
            throw GameException::gameNotActive();
        }

        return DB::transaction(function () use ($game, $giftApisFound) {
            $rounds = Round::where('game_id', $game->id)->get();
            $totalTime = $rounds->sum('time_seconds');
            $quizCount = $rounds->where('quiz_used', true)->count();
            $apisFound = Constants::APIS_PER_SESSION;

            // Gift apis logic: only award if globally available (with lock to prevent race condition)
            $giftApisAwarded = false;
            if ($giftApisFound) {
                $globalGiftCount = Game::where('gift_apis_found', true)
                    ->where('status', 'completed')
                    ->lockForUpdate()
                    ->count();
                if ($globalGiftCount < Constants::MAX_GIFT_APIS_GLOBAL) {
                    $giftApisAwarded = true;
                }
            }

            $game->update([
                'status' => 'completed',
                'total_time' => $totalTime,
                'quiz_count' => $quizCount,
                'apis_found' => $apisFound + ($giftApisAwarded ? 1 : 0),
                'gift_apis_found' => $giftApisAwarded,
                'completed_at' => Carbon::now(),
            ]);

            if ($giftApisAwarded) {
                self::forgetGiftCountCache();
            }

            $player = Player::findOrFail($game->player_id);
            $player->round_apis_found = min(
                $player->round_apis_found + $apisFound,
                Constants::MAX_APIS
            );
            if ($giftApisAwarded) {
                $player->gift_apis_found += 1;
            }
            $player->total_sessions += 1;

            if ($player->best_total_time === null || $totalTime < $player->best_total_time) {
                $player->best_total_time = $totalTime;
            }

            $player->save();
            $player->refresh();

            $rank = $this->calculatePlayerRank($player);

            $isBestTime = $player->best_total_time !== null && $player->best_total_time == $totalTime;

            return [
                'game_id' => $game->id,
                'total_time' => round($totalTime, 1),
                'quiz_count' => $quizCount,
                'round_apis_found' => $apisFound,
                'gift_apis_found' => $giftApisAwarded,
                'player_round_apis' => $player->round_apis_found,
                'player_gift_apis' => $player->gift_apis_found,
                'player_remaining_round_apis' => max(0, Constants::MAX_APIS - $player->round_apis_found),
                'is_best_time' => $isBestTime,
                'leaderboard_rank' => $rank,
            ];
        });
    }

    public function claimGiftApis(Game $game): array
    {
        if (!in_array($game->status, ['playing', 'completed'])) {
            throw GameException::gameNotActive();
        }

        if ($game->gift_apis_found) {
            return [
                'awarded' => false,
                'reason' => 'Gift already claimed for this game',
            ];
        }

        return DB::transaction(function () use ($game) {
            $globalGiftCount = Game::where('gift_apis_found', true)
                ->where('status', 'completed')
                ->lockForUpdate()
                ->count();

            if ($globalGiftCount >= Constants::MAX_GIFT_APIS_GLOBAL) {
                return [
                    'awarded' => false,
                    'reason' => 'Global gift limit reached',
                ];
            }

            $game->update([
                'gift_apis_found' => true,
                'apis_found' => $game->apis_found + 1,
            ]);

            self::forgetGiftCountCache();

            $player = Player::findOrFail($game->player_id);
            $player->gift_apis_found += 1;
            $player->save();

            return [
                'awarded' => true,
                'player_gift_apis' => $player->gift_apis_found,
                'player_total_apis' => $player->round_apis_found + $player->gift_apis_found,
            ];
        });
    }

    private function abandonActiveGames(int $playerId): ?int
    {
        $activeGame = Game::where('player_id', $playerId)
            ->where('status', 'playing')
            ->first();

        if (!$activeGame) {
            return null;
        }

        $activeGame->update([
            'status' => 'abandoned',
            'completed_at' => Carbon::now(),
        ]);

        return $activeGame->id;
    }

    private function calculatePlayerRank(Player $player): int
    {
        if ($player->total_sessions < 1) {
            return 0;
        }

        $totalApis = $player->round_apis_found + $player->gift_apis_found;

        return Player::where('total_sessions', '>', 0)
            ->where(function ($q) use ($totalApis, $player) {
                $q->where('total_apis', '>', $totalApis)
                  ->orWhere(function ($q2) use ($totalApis, $player) {
                      $q2->where('total_apis', $totalApis)
                         ->whereNotNull('best_total_time')
                         ->where('best_total_time', '<', $player->best_total_time);
                  });
            })
            ->count() + 1;
    }
}
