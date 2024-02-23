<?php

namespace App\Helpers;

use App\Models\Activity;

class ActivityHelper
{
    public static function store_avtivity($user_id, $message, $type)
    {
        $activity = new Activity;
        $activity->data_id = $user_id;
        $activity->message = $message;
        $activity->type = $type;
        $activity->save();
    }
}
