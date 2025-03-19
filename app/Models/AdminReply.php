<?php
namespace App\Models;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'reply',
        'user_id',
        'question_type',
    ];

    protected $casts = [
        'user_id'       => 'integer',
        'question_id'   => 'integer',
        'question_type' => 'string',
    ];

    protected $attributes = [
        'reply' => "",
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
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'image', 'fiqa');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function userAllQuery()
    {
        return $this->belongsTo(UserAllQuery::class, 'question_id', 'query_id');
    }
}
