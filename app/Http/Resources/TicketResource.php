<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property int $order_id
 * @property int $ticket_type_id
 * @property int|float $price
 * @property string $barcode
 */
class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'ticket_type_id' => $this->ticket_type_id,
            'price' => $this->price,
            'barcode' => $this->barcode,
        ];
    }
}
