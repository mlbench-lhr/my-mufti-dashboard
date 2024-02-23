<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $fillable = [
        'experience_startDate',
        'experience_endDate',
        'user_id',
    ];
    protected $casts = [
        'user_id' => 'integer',
    ];
}
