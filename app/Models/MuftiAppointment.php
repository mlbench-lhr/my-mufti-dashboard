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
        'duration',
        'contact_number',
        'email',
        'consultation_fee',
        'payment_method',
        'payment_id',
        'user_type',
    ];
    protected $casts = [
        'mufti_id' => 'integer',
        'user_id' => 'integer',
    ];

    protected $attributes = [
        'description' => "",
        'payment_id' => "",
        "payment_method" => "",
    ];

    public function user_detail()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'image', 'email', 'user_type');
    }

    public function mufti_detail()
    {
        return $this->belongsTo(User::class, 'mufti_id')->select('id', 'name', 'image', 'email', 'fiqa', 'phone_number');
    }

    
}
