<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    private string $token;

    public function __construct($resource, string $token = '')
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    public function toArray($request)
    {
        return [
            'user' => new UserResource($this->resource),
            'token' => $this->when($this->token, $this->token),
            'token_type' => $this->when($this->token, 'Bearer'),
        ];
    }
}
