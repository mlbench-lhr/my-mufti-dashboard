<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRamadanAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'question_id',
        'selected_option_id',
        'is_correct',
        'points_earned',
        'answered_at',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(RamadanQuestion::class, 'question_id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(RamadanOption::class, 'selected_option_id');
    }
}
