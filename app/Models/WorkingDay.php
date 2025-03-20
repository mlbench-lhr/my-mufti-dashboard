<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day_name',
        'is_available',
    ];
    
    protected $casts = [
        'user_id' => 'integer',
    ];

    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }

    public function slots()
    {
        return $this->hasMany(WorkingSlot::class, 'working_day_id', 'id');
    }
}
