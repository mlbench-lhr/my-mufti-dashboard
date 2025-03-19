<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class QuestionComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'comment',
        'user_id',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'question_id' => 'integer',
    ];

    protected $attributes = [
        'comment' => "",
    ];

    // public function getConnectionName()
    // {
    //     return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    // }

    public function getConnectionName()
    {
        return request()->is('testing/*') || request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }

    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id','name', 'image');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id','name', 'image', 'email', 'user_type');
    }
}
