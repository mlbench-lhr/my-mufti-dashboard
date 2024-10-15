<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\User;

class ReportQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'reason',
    ];

    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'image', 'email');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
