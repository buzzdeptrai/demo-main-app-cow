<?php

namespace App\MiniApps\NnvnApisGo\Services;

use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Models\Player;

class LeaderboardService
{
    public function getLeaderboard(int $limit = 10): array
    {
        $totalPlayers = Player::whereNotNull('best_total_time')->count();

        $players = Player::whereNotNull('best_total_time')
            ->orderBy('best_total_time', 'asc')
            ->limit($limit)
            ->get();

        $entries = $players->map(function ($player, $index) {
            $lastGame = Game::where('player_id', $player->id)
                ->where('status', 'completed')
                ->latest('completed_at')
                ->first();

            return [
                'rank' => $index + 1,
                'name' => $player->name,
                'total_time' => $player->best_total_time,
                'quiz_count' => $lastGame ? $lastGame->quiz_count : 0,
                'total_apis' => $player->round_apis_found + $player->gift_apis_found,
                'date' => $lastGame ? $lastGame->completed_at->format('Y-m-d') : null,
            ];
        })->toArray();

        return [
            'entries' => $entries,
            'total_players' => $totalPlayers,
        ];
    }

    public function getPlayerRank(int $playerId): array
    {
        $player = Player::findOrFail($playerId);
        $totalPlayers = Player::whereNotNull('best_total_time')->count();

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
            'quiz_count' => Game::where('player_id', $player->id)
                ->where('status', 'completed')
                ->sum('quiz_count'),
            'total_apis' => $player->round_apis_found + $player->gift_apis_found,
            'total_sessions' => $player->total_sessions,
            'total_players' => $totalPlayers,
        ];
    }
}
