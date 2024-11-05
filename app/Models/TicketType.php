<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $name
 * @property $description
 */
class TicketType extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];
}
