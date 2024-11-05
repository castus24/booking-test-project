<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $id
 * @property $event_id
 * @property $event_date
 * @property $user_id
 * @property $equal_price
 * @property $barcode
 */
class Order extends Model
{
    public const BOOK_URL = 'https://api.site.com/book';
    public const APPROVE_URL = 'https://api.site.com/approve';

    protected $fillable = [
        'event_id',
        'event_date',
        'user_id',
        'equal_price',
        'barcode'
    ];

    /**
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
