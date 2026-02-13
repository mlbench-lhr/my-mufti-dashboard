<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRamadanStat extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_points',
        'level',
        'progress_percentage',
    ];

    protected $casts = [
        'total_points' => 'integer',
        'progress_percentage' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
