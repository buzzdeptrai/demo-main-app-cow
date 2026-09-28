<?php

namespace App\MiniApps\NnvnApisGo\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'rank' => $this->resource['rank'],
            'name' => $this->resource['name'],
            'email' => $this->resource['email'],
            'total_time' => $this->resource['total_time'],
            'total_apis' => $this->resource['total_apis'],
            'total_sessions' => $this->resource['total_sessions'],
        ];
    }
}
