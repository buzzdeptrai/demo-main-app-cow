<?php

namespace App\MiniApps\NnvnApisGo\Controllers;

use App\Http\Controllers\Controller;
use App\MiniApps\NnvnApisGo\Exceptions\GameException;
use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Requests\CompleteGameRequest;
use App\MiniApps\NnvnApisGo\Requests\StartGameRequest;
use App\MiniApps\NnvnApisGo\Requests\SubmitRoundRequest;
use App\MiniApps\NnvnApisGo\Resources\GameResource;
use App\MiniApps\NnvnApisGo\Services\GameService;
use App\Traits\ApiResponse;

class GameController extends Controller
{
    use ApiResponse;

    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function start(StartGameRequest $request)
    {
        try {
            $game = $this->gameService->startGame(
                $request->validated()['player_id']
            );

            return $this->created(
                new GameResource($game),
                'Game started successfully'
            );
        } catch (GameException $e) {
            return $this->error($e->getMessage(), $e->getHttpCode(), $e->getErrorCode());
        }
    }

    public function submitRound(SubmitRoundRequest $request, int $gameId)
    {
        $game = Game::find($gameId);

        if (!$game) {
            return $this->error('Game not found', 404);
        }

        try {
            $validated = $request->validated();
            $round = $this->gameService->submitRound(
                $game,
                $validated['round_number'],
                $validated['time_seconds'],
                $validated['quiz_used']
            );

            $roundsCompleted = \App\MiniApps\NnvnApisGo\Models\Round::where('game_id', $gameId)->count();

            return $this->created([
                'round_number' => $round->round_number,
                'time_seconds' => $round->time_seconds,
                'quiz_used' => $round->quiz_used,
                'rounds_completed' => $roundsCompleted,
                'rounds_remaining' => \App\MiniApps\NnvnApisGo\Constants::TOTAL_ROUNDS - $roundsCompleted,
            ], 'Round submitted successfully');
        } catch (GameException $e) {
            return $this->error($e->getMessage(), $e->getHttpCode(), $e->getErrorCode());
        }
    }

    public function complete(CompleteGameRequest $request, int $gameId)
    {
        $game = Game::find($gameId);

        if (!$game) {
            return $this->error('Game not found', 404);
        }

        try {
            $result = $this->gameService->completeGame(
                $game,
                $request->validated()['gift_apis_found']
            );

            return $this->success($result, 'Game completed successfully');
        } catch (GameException $e) {
            return $this->error($e->getMessage(), $e->getHttpCode(), $e->getErrorCode());
        }
    }

    public function claimGiftApis(int $gameId)
    {
        $game = Game::find($gameId);

        if (!$game) {
            return $this->error('Game not found', 404);
        }

        try {
            $result = $this->gameService->claimGiftApis($game);

            return $this->success($result, $result['awarded']
                ? 'Gift apis awarded successfully'
                : $result['reason']
            );
        } catch (GameException $e) {
            return $this->error($e->getMessage(), $e->getHttpCode(), $e->getErrorCode());
        }
    }
}
