<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpFeedBack extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'description',
    ];
    protected $casts = [
        'user_id' => 'integer',
    ];

    protected $attributes = [
        'description' => "",
    ];

    // public function getConnectionName()
    // {
    //     return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    // }

    public function getConnectionName()
    {
        return request()->is('testing/*') || request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }
}
