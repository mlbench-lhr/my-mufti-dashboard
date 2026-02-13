<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamadanWeek extends Model
{
    use HasFactory;
    protected $fillable = ['week_number'];

    public function topics()
    {
        return $this->hasMany(RamadanTopic::class, 'week_id');
    }
}
