<?php

namespace App\MiniApps\NnvnApisGo\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
        ];
    }
}
