<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interest extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'interest',
        'fiqa',
    ];
    protected $casts = [
        'user_id' => 'integer',
    ];
    protected $hidden = [
        'deleted_at',
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
