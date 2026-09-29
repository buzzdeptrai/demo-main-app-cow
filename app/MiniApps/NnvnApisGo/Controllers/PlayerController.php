<?php

namespace App\MiniApps\NnvnApisGo\Controllers;

use App\Http\Controllers\Controller;
use App\MiniApps\NnvnApisGo\Exceptions\GameException;
use App\MiniApps\NnvnApisGo\Requests\RegisterPlayerRequest;
use App\MiniApps\NnvnApisGo\Resources\PlayerResource;
use App\MiniApps\NnvnApisGo\Services\GameService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    use ApiResponse;

    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function register(RegisterPlayerRequest $request)
    {
        $player = $this->gameService->registerOrLogin(
            $request->validated()['name']
        );

        return $this->success(
            new PlayerResource($player),
            'Player registered successfully'
        );
    }

    public function me(Request $request)
    {
        $id = $request->query('id');

        if (!$id) {
            return $this->error('Player ID is required', 400);
        }

        try {
            $player = $this->gameService->getPlayer((int) $id);
            return $this->success(
                new PlayerResource($player),
                'Player retrieved successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Player not found', 404);
        }
    }
}
