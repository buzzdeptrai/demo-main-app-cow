<?php

namespace App\MiniApps\NnvnApisGo\Services;

use App\MiniApps\NnvnApisGo\Constants;
use App\MiniApps\NnvnApisGo\Exceptions\GameException;
use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Models\Player;
use App\MiniApps\NnvnApisGo\Models\Round;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GameService
{
    public function registerOrLogin(string $name, string $email): Player
    {
        return Player::firstOrCreate(
            ['email' => $email],
            ['name' => $name]
        );
    }

    public function getPlayer(string $email): Player
    {
        return Player::where('email', $email)->firstOrFail();
    }

    public function startGame(int $playerId): Game
    {
        $player = Player::findOrFail($playerId);

        if ($player->total_apis_found >= Constants::MAX_APIS) {
            throw GameException::maxApisReached();
        }

        $this->abandonActiveGames($playerId);

        return Game::create([
            'player_id' => $playerId,
            'status' => 'playing',
        ]);
    }

    public function submitRound(Game $game, int $roundNumber, float $timeSeconds, bool $quizUsed): Round
    {
        if ($game->status !== 'playing') {
            throw GameException::gameNotActive();
        }

        if ($timeSeconds < Constants::MIN_ROUND_TIME || $timeSeconds > Constants::MAX_ROUND_TIME) {
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
            $apisFound = Constants::APIS_PER_SESSION + ($giftApisFound ? 1 : 0);

            $game->update([
                'status' => 'completed',
                'total_time' => $totalTime,
                'quiz_count' => $quizCount,
                'apis_found' => $apisFound,
                'gift_apis_found' => $giftApisFound,
                'completed_at' => Carbon::now(),
            ]);

            $player = Player::findOrFail($game->player_id);
            $newTotalApis = min($player->total_apis_found + $apisFound, Constants::MAX_APIS);
            $newTotalSessions = $player->total_sessions + 1;

            $updateData = [
                'total_apis_found' => $newTotalApis,
                'total_sessions' => $newTotalSessions,
            ];

            if ($player->best_total_time === null || $totalTime < $player->best_total_time) {
                $updateData['best_total_time'] = $totalTime;
            }

            $player->update($updateData);
            $player->refresh();

            $rank = $this->calculatePlayerRank($player);

            return [
                'game_id' => $game->id,
                'total_time' => round($totalTime, 2),
                'quiz_count' => $quizCount,
                'apis_found' => $apisFound,
                'total_apis' => $player->total_apis_found,
                'best_total_time' => $player->best_total_time,
                'rank' => $rank,
            ];
        });
    }

    private function abandonActiveGames(int $playerId): void
    {
        Game::where('player_id', $playerId)
            ->where('status', 'playing')
            ->update(['status' => 'abandoned']);
    }

    private function calculatePlayerRank(Player $player): int
    {
        if ($player->best_total_time === null) {
            return 0;
        }

        return Player::whereNotNull('best_total_time')
            ->where('best_total_time', '<', $player->best_total_time)
            ->count() + 1;
    }
}
