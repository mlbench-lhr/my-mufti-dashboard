<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuestionVote;
use App\Models\QuestionComment;


class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'question_category',
        'time_limit',
        'voting_option',
        'user_info',
        'user_id',
    ];
    protected $casts = [
        'question_category' => 'array',
        'user_id' => 'integer',
        'user_info' => 'integer',
        'voting_option' => 'integer',
    ];

    protected $attributes = [
        'question_category' => "[]",
        'question' => "",
    ];

    protected $hidden = [
        'question_category',
    ];

    public function votes()
    {
        return $this->hasMany(QuestionVote::class);
    }

    public function comments()
    {
        return $this->hasMany(QuestionComment::class);
    }
    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id','name', 'image');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id','name','email', 'image', 'user_type');
    }

    public function reports()
    {
        return $this->hasMany(ReportQuestion::class);
    }
}
