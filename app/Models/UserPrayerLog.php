<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrayerLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'prayer_id',
        'prayer_date',
        'status',
        'points_earned',
        'notes',
        'is_late',
    ];

    protected $casts = [
        'prayer_date' => 'date',
        'is_late' => 'boolean',
    ];

    public function prayer()
    {
        return $this->belongsTo(Prayer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
