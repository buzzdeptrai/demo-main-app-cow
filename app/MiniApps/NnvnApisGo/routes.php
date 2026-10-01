<?php

use Illuminate\Support\Facades\Route;
use App\MiniApps\NnvnApisGo\Controllers\PlayerController;
use App\MiniApps\NnvnApisGo\Controllers\GameController;
use App\MiniApps\NnvnApisGo\Controllers\LeaderboardController;
use App\MiniApps\NnvnApisGo\Controllers\QuizController;
use App\MiniApps\NnvnApisGo\Controllers\BoxController;

/*
|--------------------------------------------------------------------------
| NNVN Apis Go - Mini App Routes
|--------------------------------------------------------------------------
|
| No auth:sanctum required (email-based identification).
|
*/

// Player
Route::post('players/register', [PlayerController::class, 'register']);
Route::get('players/me', [PlayerController::class, 'me']);

// Game
Route::post('games/start', [GameController::class, 'start']);
Route::post('games/{gameId}/rounds', [GameController::class, 'submitRound']);
Route::post('games/{gameId}/complete', [GameController::class, 'complete']);
Route::post('games/{gameId}/gift-apis', [GameController::class, 'claimGiftApis']);

// Leaderboard
Route::get('leaderboard', [LeaderboardController::class, 'index']);
Route::get('leaderboard/me', [LeaderboardController::class, 'me']);

// Quiz
Route::get('quiz/random', [QuizController::class, 'random']);
Route::post('quiz/answer', [QuizController::class, 'answer']);

// Boxes
Route::get('boxes/config', [BoxController::class, 'config']);
Route::post('boxes/click', [BoxController::class, 'click']);
