<?php

namespace App\Models;

use App\Models\EventQuestion;
use App\Models\EventScholar;
use App\Models\SaveEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'image',
        'event_title',
        'event_category',
        'question_category',
        'date',
        'duration',
        'location',
        'latitude',
        'longitude',
        'about',
        'event_status',
        'reason',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
        'event_status' => 'integer',
        'event_category' => 'array',
        'question_category' => 'array',
    ];

    protected $attributes = [
        'event_category' => "[]",
        'question_category' => "[]",
        'about' => "",
    ];
    protected $hidden = [
        'reason',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            EventScholar::where('event_id', $model->id)->delete();
            EventQuestion::where('event_id', $model->id)->delete();
            SaveEvent::where('event_id', $model->id)->delete();
        });
    }

    public function scholars()
    {
        return $this->hasMany(EventScholar::class);
    }
    public function hosted_by()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function event_questions()
    {
        return $this->hasMany(EventQuestion::class, 'event_id', 'id');
    }
}
