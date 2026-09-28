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
                'email' => $this->maskEmail($player->email),
                'total_time' => $player->best_total_time,
                'total_apis' => $player->total_apis_found,
                'total_sessions' => $player->total_sessions,
            ];
        })->toArray();
    }

    public function getPlayerRank(string $email): array
    {
        $player = Player::where('email', $email)->firstOrFail();

        $rank = 0;
        if ($player->best_total_time !== null) {
            $rank = Player::whereNotNull('best_total_time')
                ->where('best_total_time', '<', $player->best_total_time)
                ->count() + 1;
        }

        return [
            'rank' => $rank,
            'name' => $player->name,
            'email' => $this->maskEmail($player->email),
            'total_time' => $player->best_total_time,
            'total_apis' => $player->total_apis_found,
            'total_sessions' => $player->total_sessions,
        ];
    }

    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $local = $parts[0];
        $masked = substr($local, 0, 3) . '...';

        return $masked . 'com';
    }
}
