<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpFeedBack extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'description',
    ];
    protected $casts = [
        'user_id' => 'integer',
    ];
}
