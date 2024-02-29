<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Activity;
use App\Models\Degree;
use App\Models\Event;
use App\Models\EventQuestion;
use App\Models\EventScholar;
use App\Models\Experience;
use App\Models\HelpFeedBack;
use App\Models\Interest;
use App\Models\Mufti;
use App\Models\MuftiAppointment;
use App\Models\Notification;
use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\QuestionVote;
use App\Models\SaveEvent;
use App\Models\ScholarReply;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'phone_number',
        'fiqa',
        'mufti_status',
        'user_type',
        'device_id',
        'a_code',
        'g_code',
        'email_code',
    ];
    protected $attributes = [
        'a_code' => "",
        'g_code' => "",
        'device_id' => "",
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'mufti_status' => 'integer',
        'email_code' => 'integer',
    ];
    protected $dates = ['deleted_at'];
    public function getDeletedAtAttribute($value)
    {
        return $value !== null ? $value : '';
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {

            UserAllQuery::where('user_id', $model->id)->delete();
            UserAllQuery::where('mufti_id', $model->id)->delete();
            ScholarReply::where('user_id', $model->id)->delete();
            SaveEvent::where('user_id', $model->id)->delete();
            QuestionVote::where('user_id', $model->id)->delete();
            QuestionComment::where('user_id', $model->id)->delete();
            Question::where('user_id', $model->id)->delete();
            Notification::where('user_id', $model->id)->delete();
            MuftiAppointment::where('user_id', $model->id)->delete();
            MuftiAppointment::where('mufti_id', $model->id)->delete();
            Mufti::where('user_id', $model->id)->delete();
            Interest::where('user_id', $model->id)->delete();
            HelpFeedBack::where('user_id', $model->id)->delete();
            Experience::where('user_id', $model->id)->delete();
            EventQuestion::where('user_id', $model->id)->delete();
            Degree::where('user_id', $model->id)->delete();
            Activity::where('data_id', $model->id)->delete();
            UserQuery::where('user_id', $model->id)->delete();


        });
    }

    public function interests()
    {
        return $this->hasMany(Interest::class)->select('id', 'user_id', 'interest');
    }

    public function mufti_detail()
    {
        return $this->hasOne(Mufti::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function deleteWithRelated()
    {
        $eventsToDelete = $this->events;

        $eventIdsToDelete = $eventsToDelete->pluck('id')->toArray();

        EventScholar::whereIn('event_id', $eventIdsToDelete)->delete();
        EventQuestion::whereIn('event_id', $eventIdsToDelete)->delete();
        SaveEvent::whereIn('event_id', $eventIdsToDelete)->delete();

        $this->events()->delete();
    }

}
