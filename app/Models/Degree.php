<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Degree extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'degree_image',
        'degree_title',
        'institute_name',
        'degree_startDate',
        'degree_endDate',
        'user_id',
    ];
    protected $casts = [
        'user_id' => 'integer',
    ];
    protected $hidden = [
        'deleted_at',
    ];

    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }
}
