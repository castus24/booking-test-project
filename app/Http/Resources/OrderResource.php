<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property int $event_id
 * @property string $event_date
 * @property int $user_id
 * @property int|float $equal_price
 * @property string $barcode
 * @property Ticket $tickets
 */
class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'event_date' => $this->event_date,
            'user_id' => $this->user_id,
            'equal_price' => $this->equal_price,
            'barcode' => $this->barcode,
            'tickets' => TicketResource::collection($this->tickets)
        ];
    }
}
