<?php

namespace App\MiniApps\NnvnApisGo\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitRoundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'round_number' => ['required', 'integer', 'in:1'],
            'time_seconds' => ['required', 'numeric', 'min:2'],
            'quiz_used' => ['required', 'boolean'],
        ];
    }
}
