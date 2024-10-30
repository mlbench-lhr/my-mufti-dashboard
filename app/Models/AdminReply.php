<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AdminReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'reply',
        'user_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'question_id' => 'integer',
    ];

    protected $attributes = [
        'reply' => "",
    ];

    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'image', 'fiqa');
    }
}
