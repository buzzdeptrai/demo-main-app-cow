<?php

namespace App\Http\Controllers\Api\V1\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Http\Resources\MiniAppResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BaseAppController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $miniApp = $request->attributes->get('mini_app');

        return $this->success(
            new MiniAppResource($miniApp->load('creator')),
            'App info retrieved successfully'
        );
    }

    public function settings(Request $request)
    {
        $miniApp = $request->attributes->get('mini_app');
        $settings = $miniApp->settings;

        $formatted = $settings->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->casted_value];
        });

        return $this->success($formatted, 'Settings retrieved successfully');
    }

    public function media(Request $request)
    {
        $miniApp = $request->attributes->get('mini_app');
        $collection = $request->input('collection', 'assets');

        $media = $miniApp->getMedia($collection);

        return $this->success(
            MediaResource::collection($media),
            'Media retrieved successfully'
        );
    }
}
