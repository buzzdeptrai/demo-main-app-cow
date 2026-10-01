<?php

namespace App\MiniApps\NnvnApisGo\Controllers;

use App\Http\Controllers\Controller;
use App\MiniApps\NnvnApisGo\Constants;
use App\MiniApps\NnvnApisGo\Models\BoxClick;
use App\MiniApps\NnvnApisGo\Models\BoxConfig;
use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Requests\BoxClickRequest;
use App\Traits\ApiResponse;
use Carbon\Carbon;

class BoxController extends Controller
{
    use ApiResponse;

    private const TOTAL_BOXES = 16;

    public function config()
    {
        $activeConfigs = BoxConfig::where('is_active', true)
            ->orderBy('box_index')
            ->get();

        // Check if gift apis still available globally
        $globalGiftCount = Game::where('gift_apis_found', true)
            ->where('status', 'completed')
            ->count();
        $giftAvailable = $globalGiftCount < Constants::MAX_GIFT_APIS_GLOBAL;

        $apisIndex = $giftAvailable ? rand(0, self::TOTAL_BOXES - 1) : null;

        $boxes = [];
        foreach ($activeConfigs as $config) {
            $boxes[] = [
                'index' => $config->box_index,
                'url' => $config->url,
                'label' => $config->label,
            ];
        }

        return $this->success([
            'boxes' => $boxes,
            'apis_index' => $apisIndex,
            'gift_available' => $giftAvailable,
            'total_boxes' => self::TOTAL_BOXES,
        ], 'Box configuration retrieved successfully');
    }

    public function click(BoxClickRequest $request)
    {
        $validated = $request->validated();

        $game = Game::find($validated['game_id']);

        if (!$game || !in_array($game->status, ['playing', 'completed'])) {
            return $this->error('Game not found or not active', 404);
        }

        $boxIndex = (int) $validated['box_index'];
        $boxConfig = BoxConfig::where('box_index', $boxIndex)
            ->where('is_active', true)
            ->first();

        $isApis = $boxConfig === null;
        $urlOpened = $boxConfig ? $boxConfig->url : null;

        $boxClick = BoxClick::create([
            'game_id' => $game->id,
            'round_number' => $validated['round_number'],
            'box_index' => $boxIndex,
            'is_apis' => $isApis,
            'is_correct' => $isApis,
            'url_opened' => $urlOpened,
            'clicked_at' => Carbon::now(),
        ]);

        return $this->success([
            'is_apis' => $isApis,
            'url' => $urlOpened,
        ], $isApis ? 'You found the Apis!' : 'This box contains a link');
    }
}
