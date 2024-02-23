<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    use HasFactory;
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
}
