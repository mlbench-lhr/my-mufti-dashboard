<?php

namespace App\Models;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'user_id',
    ];
    protected $casts = [
        'event_id' => 'integer',
        'user_id' => 'integer',
    ];
    public function event_detail()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    // public function getConnectionName()
    // {
    //     return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    // }

    public function getConnectionName()
    {
        return request()->is('testing/*') || request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }
}
