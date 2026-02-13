<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanTopic extends Model
{
    use HasFactory;
    protected $fillable = ['week_id', 'day_number', 'title', 'subtitle'];

    public function questions()
    {
        return $this->hasMany(RamadanQuestion::class, 'topic_id');
    }

    public function week()
    {
        return $this->belongsTo(RamadanWeek::class, 'week_id');
    }
}
