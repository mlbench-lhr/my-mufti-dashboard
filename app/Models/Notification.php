<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'question_id',
        'event_id',
        'appointment_id',

    ];
    protected $casts = [
        'user_id' => 'integer',
    ];

    protected $attributes = [
        'body' => "",
    ];

    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }
}
