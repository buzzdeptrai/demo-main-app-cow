<?php

namespace App\MiniApps\NnvnApisGo\Controllers;

use App\Http\Controllers\Controller;
use App\MiniApps\NnvnApisGo\Constants;
use App\MiniApps\NnvnApisGo\Models\BoxClick;
use App\MiniApps\NnvnApisGo\Models\BoxConfig;
use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Requests\BoxClickRequest;
use App\MiniApps\NnvnApisGo\Services\GameService;
use App\Traits\ApiResponse;
use Carbon\Carbon;

class BoxController extends Controller
{
    use ApiResponse;

    public function config()
    {
        $giftAvailable = GameService::globalGiftCount() < Constants::MAX_GIFT_APIS_GLOBAL;

        // Số slot "đặc biệt" (không phải link) = apis + message + ecard,
        // để số link luôn khớp với số link config đã fetch.
        $specialCount = Constants::BOX_APIS_COUNT + Constants::BOX_MESSAGE_COUNT + Constants::BOX_ECARD_COUNT;
        $linkCount = Constants::TOTAL_BOXES - $specialCount;

        $linkConfigs = BoxConfig::where('is_active', true)
            ->orderBy('box_index')
            ->limit($linkCount)
            ->get();

        $indices = range(0, Constants::TOTAL_BOXES - 1);
        shuffle($indices);

        // Phân bổ slot đặc biệt từ đầu danh sách đã shuffle.
        $cursor = 0;

        // Khi hết gift toàn cục: bỏ ô apis, slot đó dồn thành message (tránh ô trống).
        $apisIndices = [];
        if ($giftAvailable) {
            $apisIndices = array_slice($indices, $cursor, Constants::BOX_APIS_COUNT);
            $cursor += Constants::BOX_APIS_COUNT;
        }

        $ecardIndices = array_slice($indices, $cursor, Constants::BOX_ECARD_COUNT);
        $cursor += Constants::BOX_ECARD_COUNT;

        // Gán mỗi ô ecard một thiệp khác nhau (cycle nếu số ô > số thiệp).
        $ecards = Constants::BOX_ECARDS;
        $ecardMap = [];
        foreach (array_values($ecardIndices) as $pos => $boxIdx) {
            $ecardMap[$boxIdx] = $ecards[$pos % count($ecards)];
        }

        // Các slot đặc biệt còn lại (gồm cả slot apis khi hết gift) đều thành message.
        $messageSlotCount = $specialCount - count($apisIndices) - count($ecardIndices);
        $messageIndices = array_slice($indices, $cursor, $messageSlotCount);

        // Gán mỗi ô message một câu khác nhau (cycle nếu số ô > số câu).
        $messages = Constants::BOX_MESSAGES;
        $messageMap = [];
        foreach (array_values($messageIndices) as $pos => $boxIdx) {
            $messageMap[$boxIdx] = $messages[$pos % count($messages)];
        }

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
            } elseif (isset($ecardMap[$i])) {
                $boxes[] = [
                    'index' => $i,
                    'type' => 'ecard',
                    'ecard_key' => $ecardMap[$i]['key'],
                    'url' => '',
                    'label' => '',
                    'message' => $ecardMap[$i]['vi'],
                    'message_en' => $ecardMap[$i]['en'],
                ];
            } elseif (isset($messageMap[$i])) {
                $boxes[] = [
                    'index' => $i,
                    'type' => 'message',
                    'url' => '',
                    'label' => '',
                    'message' => $messageMap[$i]['vi'],
                    'message_en' => $messageMap[$i]['en'],
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

        BoxClick::create([
            'game_id' => $game->id,
            'round_number' => $validated['round_number'],
            'box_index' => $boxIndex,
            'box_type' => $boxType,
            'is_apis' => $isApis,
            'is_correct' => $isApis,
            'url_opened' => $boxType === 'link' ? ($validated['url'] ?? null) : null,
            'message_text' => $boxType === 'message' ? ($validated['message'] ?? null) : null,
            'ecard_key' => $boxType === 'ecard' ? ($validated['ecard_key'] ?? null) : null,
            'clicked_at' => Carbon::now(),
        ]);

        return $this->success([
            'is_apis' => $isApis,
            'type' => $boxType,
        ], $isApis ? 'You found the Apis!' : 'Keep looking!');
    }
}
