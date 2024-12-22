<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'message',
        'response',
        'context'
    ];

    protected $casts = [
        'context' => 'array'
    ];
}