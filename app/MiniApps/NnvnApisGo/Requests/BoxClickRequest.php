<?php

namespace App\MiniApps\NnvnApisGo\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoxClickRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'game_id' => ['required', 'integer', 'exists:nnvn_games,id'],
            'round_number' => ['required', 'integer', 'in:1'],
            'box_index' => ['required', 'integer', 'between:0,7'],
            'box_type' => ['sometimes', 'string', 'in:link,apis,message'],
        ];
    }
}
