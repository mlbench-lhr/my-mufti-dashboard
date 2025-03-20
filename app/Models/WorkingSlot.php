<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'working_day_id',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }

    public function appointments()
    {
        return $this->hasMany(MuftiAppointment::class, 'selected_slot', 'id');
    }
}
