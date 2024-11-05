<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $id
 * @property $name
 * @property $description
 * @property $schedule_date
 */
class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'schedule_date',
    ];

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
