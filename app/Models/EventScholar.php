<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventScholar extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'user_id',
        'image',
        'name',
        'fiqa',
        'category',
    ];
    protected $casts = [
        'event_id' => 'integer',
        'user_id' => 'integer',
        'category' => 'array',
    ];
}
