<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages'; // Define table name

    protected $fillable = ['user_id'];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public $timestamps = true; // Enable timestamps

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}