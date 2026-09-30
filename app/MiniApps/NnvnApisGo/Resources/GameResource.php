<?php

namespace App\MiniApps\NnvnApisGo\Resources;

use App\MiniApps\NnvnApisGo\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'game_id' => $this->id,
            'player_id' => $this->player_id,
            'status' => $this->status,
            'total_rounds' => Constants::TOTAL_ROUNDS,
            'gift_apis_available' => $this->gift_apis_available ?? false,
        ];

        if ($this->abandoned_game_id) {
            $data['abandoned_game_id'] = $this->abandoned_game_id;
        }

        return $data;
    }
}
