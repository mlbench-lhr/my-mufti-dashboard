<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAllQuery extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'query_id',
        'user_id',
        'mufti_id',
        'status',
        'reason',
    ];


    protected $casts = [
        'status' => 'integer',
        'mufti_id' => 'integer',
        'user_id' => 'integer',
        'query_id' => 'integer',
        'created_at' => "datetime:Y-m-d H:i:s",
    ];

    protected $attributes = [
        'question' => "",
    ];
    
    public function mufti_detail()
    {
        return $this->belongsTo(User::class, 'mufti_id', 'id');
    }
    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
