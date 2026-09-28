<?php

namespace App\MiniApps\NnvnApisGo\Resources;

use App\MiniApps\NnvnApisGo\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'game_id' => $this->id,
            'player_id' => $this->player_id,
            'status' => $this->status,
            'total_rounds' => Constants::TOTAL_ROUNDS,
        ];
    }
}
