<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRamadanQuizProgress extends Model
{
    use HasFactory;
    protected $table = 'user_ramadan_quiz_progress';

    protected $fillable = [
        'user_id',
        'topic_id',
        'points_earned',
        'correct_answers',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(RamadanTopic::class, 'topic_id');
    }

    public function answers()
    {
        return $this->hasMany(UserRamadanAnswer::class, 'user_id', 'user_id');
    }
}
