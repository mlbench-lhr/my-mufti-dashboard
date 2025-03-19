<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'data_id',
        'message',
        'type',
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
