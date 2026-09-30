<?php

namespace App\MiniApps\NnvnApisGo\Resources;

use App\MiniApps\NnvnApisGo\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    public function toArray($request): array
    {
        $roundApis = $this->round_apis_found ?? 0;
        $giftApis = $this->gift_apis_found ?? 0;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'round_apis_found' => $roundApis,
            'gift_apis_found' => $giftApis,
            'total_apis' => $roundApis + $giftApis,
            'remaining_round_apis' => max(0, Constants::MAX_APIS - $roundApis),
            'can_play' => $roundApis < Constants::MAX_APIS,
            'total_sessions' => $this->total_sessions ?? 0,
            'best_total_time' => $this->best_total_time,
        ];
    }
}
