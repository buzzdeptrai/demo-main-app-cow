<?php

namespace App\MiniApps\NnvnApisGo\Services;

use App\MiniApps\NnvnApisGo\Models\Player;

class LeaderboardService
{
    public function getLeaderboard(int $limit = 10): array
    {
        $players = Player::whereNotNull('best_total_time')
            ->orderBy('best_total_time', 'asc')
            ->limit($limit)
            ->get();

        return $players->map(function ($player, $index) {
            return [
                'rank' => $index + 1,
                'name' => $player->name,
                'total_time' => $player->best_total_time,
                'total_apis' => $player->total_apis_found,
                'total_sessions' => $player->total_sessions,
            ];
        })->toArray();
    }

    public function getPlayerRank(int $playerId): array
    {
        $player = Player::findOrFail($playerId);

        $rank = 0;
        if ($player->best_total_time !== null) {
            $rank = Player::whereNotNull('best_total_time')
                ->where('best_total_time', '<', $player->best_total_time)
                ->count() + 1;
        }

        return [
            'rank' => $rank,
            'name' => $player->name,
            'total_time' => $player->best_total_time,
            'total_apis' => $player->total_apis_found,
            'total_sessions' => $player->total_sessions,
        ];
    }
}
