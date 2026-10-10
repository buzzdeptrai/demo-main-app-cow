<?php

namespace App\MiniApps\NnvnApisGo\Services;

use App\MiniApps\NnvnApisGo\Models\Player;
use Illuminate\Support\Facades\Cache;

class LeaderboardService
{
    private const CACHE_TTL = 60; // seconds — leaderboard tolerates slight staleness

    public function getLeaderboard(int $limit = 10): array
    {
        return Cache::remember("nnvn:leaderboard:{$limit}", self::CACHE_TTL, function () use ($limit) {
            $totalPlayers = Player::where('total_sessions', '>', 0)->count();

            $players = Player::where('total_sessions', '>', 0)
                ->orderBy('total_apis', 'desc')
                ->orderBy('best_total_time', 'asc')
                ->limit($limit)
                ->get();

            $entries = $players->map(function ($player, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $player->name,
                    'round_apis' => $player->round_apis_found,
                    'gift_apis' => $player->gift_apis_found,
                    'total_apis' => $player->round_apis_found + $player->gift_apis_found,
                    'best_time' => $player->best_total_time,
                    'total_sessions' => $player->total_sessions,
                ];
            })->toArray();

            return [
                'entries' => $entries,
                'total_players' => $totalPlayers,
            ];
        });
    }

    public function getPlayerRank(int $playerId): array
    {
        $player = Player::findOrFail($playerId);
        $totalPlayers = Player::where('total_sessions', '>', 0)->count();
        $totalApis = $player->round_apis_found + $player->gift_apis_found;

        $rank = 0;
        if ($player->total_sessions > 0) {
            $rank = Player::where('total_sessions', '>', 0)
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

        return [
            'rank' => $rank,
            'name' => $player->name,
            'round_apis' => $player->round_apis_found,
            'gift_apis' => $player->gift_apis_found,
            'total_apis' => $totalApis,
            'best_time' => $player->best_total_time,
            'total_sessions' => $player->total_sessions,
            'total_players' => $totalPlayers,
        ];
    }
}
