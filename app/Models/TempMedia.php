<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempMedia extends Model
{
    protected $table = 'temp_media'; // Define table name

    protected $fillable = [
        'temp_id',
        'media',
        //'media_type',
        'degree_title',
        'institute_name',
        'degree_startDate',
        'degree_endDate',
    ];

    protected $casts = [
        'index' => 'integer',
    ];

    public $timestamps = true; // Enable timestamps

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'temp_id', 'id');
    }
}