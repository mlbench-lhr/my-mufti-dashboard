<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuftiAppointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'mufti_id',
        'category',
        'description',
        'date',
        'selected_slot',
        'duration',
        'contact_number',
        'email',
        'consultation_fee',
        'payment_method',
        'payment_id',
        'user_type',
        'status',
    ];
    protected $casts = [
        'mufti_id' => 'integer',
        'user_id' => 'integer',
        'status' => 'integer',
        'duration' => 'string',
    ];

    

    protected $attributes = [
        'description' => "",
        'payment_id' => "",
        "payment_method" => "",
    ];

    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }

    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'image', 'email', 'user_type');
    }

    public function book_slot()
    {
        return $this->belongsTo(WorkingSlot::class, 'selected_slot')->select('id', 'start_time', 'end_time');
    }

    public function mufti_detail()
    {
        return $this->belongsTo(User::class, 'mufti_id')->select('id', 'name', 'image', 'email', 'fiqa', 'phone_number');
    }
    public function getDurationAttribute($value)
    {
        return $value ?? ''; 
    }

    
}
