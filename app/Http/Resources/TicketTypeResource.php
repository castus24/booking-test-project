<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $name
 * @property string $description
 */
class TicketTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
