<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'integer'],
            'event_date' => ['required', 'date'],
            'user_id' => ['required', 'integer'],
            'tickets' => ['required', 'array'],
            'tickets.*.ticket_type' => ['required', 'string'],
            'tickets.*.price' => ['required', 'integer', 'min:0'],
            'tickets.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
