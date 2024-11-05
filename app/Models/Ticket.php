<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $id
 * @property $order_id
 * @property $ticket_type_id
 * @property $price
 * @property $barcode
 */
class Ticket extends Model
{
    protected $fillable = [
        'order_id',
        'ticket_type_id',
        'price',
        'barcode'
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }
}
