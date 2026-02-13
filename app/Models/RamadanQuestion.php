<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanQuestion extends Model
{
    use HasFactory;
    protected $fillable = ['topic_id', 'question', 'points'];

    public function topic()
    {
        return $this->belongsTo(RamadanTopic::class, 'topic_id');
    }

    public function options()
    {
        return $this->hasMany(RamadanOption::class, 'question_id');
    }
}
