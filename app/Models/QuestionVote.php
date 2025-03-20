<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class QuestionVote extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'vote',
        'user_id',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'question_id' => 'integer',
        'vote' => 'integer',
    ];

    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }
}
