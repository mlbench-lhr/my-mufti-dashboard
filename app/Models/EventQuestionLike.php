<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventQuestionLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_question_id',
        'user_id',
    ];

    protected $casts = [
        'event_question_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }

}
