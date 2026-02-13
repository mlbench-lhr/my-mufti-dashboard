<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrayerStat extends Model
{
    use HasFactory;
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'current_streak',
        'longest_streak',
        'total_prayers_offered',
        'total_points',
        'last_completed_date',
    ];

    protected $casts = [
        'last_completed_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
