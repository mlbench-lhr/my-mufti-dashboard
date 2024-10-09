<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserAllQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'user_id',
        'fiqa',
        'category',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'category' => 'array',
        'created_at' => "datetime:Y-m-d\TH:i:s",
    ];

    protected $attributes = [
        'question' => "",
        'category' => "[]",
    ];
    public function all_question()
    {
        return $this->hasMany(UserAllQuery::class, 'query_id', 'id');
    }

    public function questioned_by()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'image', 'user_type', 'email');
    }
}
