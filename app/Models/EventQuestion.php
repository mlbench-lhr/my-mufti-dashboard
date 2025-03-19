<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'user_id',
        'category',
        'question',
        'answer',
    ];
    protected $casts = [
        'event_id' => 'integer',
        'user_id' => 'integer',
    ];

    protected $attributes = [
        'question' => "",
        'answer' => "",
    ];

    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }

    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(EventQuestionLike::class);
    }

}
