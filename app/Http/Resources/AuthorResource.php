<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'patronymic ' => $this->patronymic,
            'active' => $this->active,
            'created_at' => $this->created_at,
        ];
    }
}
