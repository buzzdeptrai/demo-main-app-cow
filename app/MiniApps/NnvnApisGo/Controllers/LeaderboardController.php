<?php

namespace App\MiniApps\NnvnApisGo\Controllers;

use App\Http\Controllers\Controller;
use App\MiniApps\NnvnApisGo\Services\LeaderboardService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    use ApiResponse;

    private LeaderboardService $leaderboardService;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 10);
        $limit = max(1, min($limit, 100));

        $leaderboard = $this->leaderboardService->getLeaderboard($limit);

        return $this->success($leaderboard, 'Leaderboard retrieved successfully');
    }

    public function me(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return $this->error('Email is required', 400);
        }

        try {
            $rank = $this->leaderboardService->getPlayerRank($email);
            return $this->success($rank, 'Player rank retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Player not found', 404);
        }
    }
}
