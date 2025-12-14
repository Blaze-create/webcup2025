<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    protected $fillable = ['from_id', 'to_id', 'preview', 'accepted_at', 'declined_at'];

    protected $casts = [
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
    ];
}
