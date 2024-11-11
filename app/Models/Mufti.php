<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mufti extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'fiqa',
        'reason',
        'status',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'status' => 'integer',
    ];
}
