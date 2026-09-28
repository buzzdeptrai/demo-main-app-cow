<?php

namespace App\Http\Requests\MiniApp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMiniAppRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $miniAppId = $this->route('mini_app');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'sometimes', 'string', 'max:255',
                Rule::unique('mini_apps', 'slug')->ignore($miniAppId),
            ],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:active,inactive,maintenance'],
            'version' => ['sometimes', 'string', 'max:20'],
            'api_rate_limit' => ['sometimes', 'integer', 'min:1', 'max:1000'],
            'webhook_url' => ['nullable', 'url', 'max:500'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
