<?php

namespace App\MiniApps\NnvnApisGo\Resources;

use App\MiniApps\NnvnApisGo\Constants;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    public function toArray($request): array
    {
        $totalApis = $this->total_apis_found ?? 0;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_apis_found' => $totalApis,
            'total_sessions' => $this->total_sessions ?? 0,
            'best_total_time' => $this->best_total_time,
            'can_play' => $totalApis < Constants::MAX_APIS,
            'remaining_apis' => max(0, Constants::MAX_APIS - $totalApis),
        ];
    }
}
