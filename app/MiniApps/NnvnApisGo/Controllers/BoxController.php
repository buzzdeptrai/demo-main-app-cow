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

    public function config()
    {
        $globalGiftCount = Game::where('gift_apis_found', true)
            ->where('status', 'completed')
            ->count();
        $giftAvailable = $globalGiftCount < Constants::MAX_GIFT_APIS_GLOBAL;

        $linkConfigs = BoxConfig::where('is_active', true)
            ->orderBy('box_index')
            ->limit(Constants::TOTAL_BOXES - Constants::BOX_APIS_COUNT - Constants::BOX_MESSAGE_COUNT)
            ->get();

        $indices = range(0, Constants::TOTAL_BOXES - 1);
        shuffle($indices);

        $apisIndices = $giftAvailable
            ? array_slice($indices, 0, Constants::BOX_APIS_COUNT)
            : [];
        $messageIndices = array_slice($indices, Constants::BOX_APIS_COUNT, Constants::BOX_MESSAGE_COUNT);

        $message = Constants::BOX_MESSAGES[array_rand(Constants::BOX_MESSAGES)];
        $messageEn = Constants::BOX_MESSAGES_EN[array_rand(Constants::BOX_MESSAGES_EN)];

        $boxes = [];
        $linkIndex = 0;

        for ($i = 0; $i < Constants::TOTAL_BOXES; $i++) {
            if (in_array($i, $apisIndices)) {
                $boxes[] = [
                    'index' => $i,
                    'type' => 'apis',
                    'url' => '',
                    'label' => '',
                    'message' => null,
                ];
            } elseif (in_array($i, $messageIndices)) {
                $boxes[] = [
                    'index' => $i,
                    'type' => 'message',
                    'url' => '',
                    'label' => '',
                    'message' => $message,
                    'message_en' => $messageEn,
                ];
            } else {
                $config = $linkConfigs[$linkIndex] ?? null;
                $linkIndex++;
                $boxes[] = [
                    'index' => $i,
                    'type' => 'link',
                    'url' => $config ? $config->url : '',
                    'label' => $config ? $config->label : '',
                    'message' => null,
                ];
            }
        }

        return $this->success([
            'boxes' => $boxes,
            'gift_available' => $giftAvailable,
            'total_boxes' => Constants::TOTAL_BOXES,
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
        $boxType = $validated['box_type'] ?? 'link';
        $isApis = $boxType === 'apis';

        $boxConfig = BoxConfig::where('box_index', $boxIndex)
            ->where('is_active', true)
            ->first();

        BoxClick::create([
            'game_id' => $game->id,
            'round_number' => $validated['round_number'],
            'box_index' => $boxIndex,
            'is_apis' => $isApis,
            'is_correct' => $isApis,
            'url_opened' => $boxConfig ? $boxConfig->url : null,
            'clicked_at' => Carbon::now(),
        ]);

        return $this->success([
            'is_apis' => $isApis,
            'type' => $boxType,
        ], $isApis ? 'You found the Apis!' : 'Keep looking!');
    }
}
