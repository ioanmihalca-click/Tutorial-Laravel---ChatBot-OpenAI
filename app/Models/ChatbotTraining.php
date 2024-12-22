<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotTraining extends Model
{
    protected $table = 'chatbot_training'; // Specificăm numele corect al tabelei

    protected $fillable = [
        'content',
        'category',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer'
    ];
}