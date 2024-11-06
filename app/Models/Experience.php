<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'experience_startDate',
        'experience_endDate',
        'user_id',
    ];
    protected $casts = [
        'user_id' => 'integer',
    ];
    protected $hidden = [
        'deleted_at',
    ];
}
