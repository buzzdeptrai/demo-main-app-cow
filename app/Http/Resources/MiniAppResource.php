<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MiniAppResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'version' => $this->version,
            'api_rate_limit' => $this->api_rate_limit,
            'webhook_url' => $this->webhook_url,
            'meta' => $this->meta,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'settings' => $this->whenLoaded('settings', function () {
                return $this->settings->map(function ($setting) {
                    return [
                        'key' => $setting->key,
                        'value' => $setting->casted_value,
                        'type' => $setting->type,
                    ];
                });
            }),
            'media_counts' => [
                'logo' => $this->getMedia('logo')->count(),
                'assets' => $this->getMedia('assets')->count(),
                'screenshots' => $this->getMedia('screenshots')->count(),
            ],
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
