<?php

namespace App\Http\Controllers\Notification;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserNotification extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function user_all_notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $page = $request->input('page', 1);
        $perPage = 10;
        $totalPages = ceil(Notification::where('user_id', $request->user_id)->count() / $perPage);

        $notifications = Notification::forPage($page, $perPage)
        ->orderBy('created_at', 'desc')
        ->where('user_id', $request->user_id)
        ->get(['id', 'title', 'body as message','event_id','question_id', 'created_at']);

        $notifications->transform(function ($notification) {
            $title = strtolower($notification->title);
        
            if (str_contains($title, 'new appointment request received')) {
                $notification->notification_type = 'request_new_appointment';
            } elseif (str_contains($title, 'event request update')) {
                $notification->notification_type = 'event_schedule_updated';
            } elseif (str_contains($title, 'event invitation')) {
                $notification->notification_type = 'event_invitation';
            } elseif (str_contains($title, 'become scholar request update')) {
                $notification->notification_type = 'scholar_request_update';
            } elseif (str_contains($title, 'become lifecoach request update')) {
                $notification->notification_type = 'lifecoach_request_update';
            } elseif (str_contains($title, 'question request update')) {
                $notification->notification_type = 'question_request_update';
            } elseif (str_contains($title, 'asked question')) {
                $notification->notification_type = 'question_asked';
            } elseif (str_contains($title, 'private question update')) {
                $notification->notification_type = 'private_question_update';
            } elseif (str_contains($title, 'public question update')) {
                $notification->notification_type = 'public_question_update';
            } elseif (str_contains($title, 'question request sent')) {
                $notification->notification_type = 'question_request_sent';
            } elseif (str_contains($title, 'deletion request update')) {
                $notification->notification_type = 'deletion_request_update';
            } elseif (str_contains($title, 'admin replied')) {
                $notification->notification_type = 'admin_replied';
            } elseif (str_contains($title, 'reply declined')) {
                $notification->notification_type = 'admin_replied';
            } elseif (str_contains($title, 'new reply to your question')) {
                $notification->notification_type = 'new_reply';
            } elseif (str_contains($title, 'reply updated to your question')) {
                $notification->notification_type = 'reply_updated';
            } else {
                $notification->notification_type = 'general';
            }
        
            return $notification;
        });

        $response = [
            'status' => true,
            'message' => 'User All Notifications!',
            'totalpages' => $totalPages,
            'data' => $notifications,
        ];
        return response()->json($response, 200);
    }

    public function delete_notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $notification = Notification::where('id', $request->notification_id)->first();

        if (!$notification) {
            return ResponseHelper::jsonResponse(false, 'Notification Not Found');
        }
        $notification->delete();
        return ResponseHelper::jsonResponse(true, 'Notification deleted Successfully!');

    }

    public function message_notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required',
            'sender_id' => 'required',
            'message' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $receiver = User::where('id', $request->receiver_id)->first();


        if (!$receiver) {
            return ResponseHelper::jsonResponse(false, 'Receiver Not Found');
        }

        $sender = User::where('id', $request->sender_id)->first();

        if (!$sender) {
            return ResponseHelper::jsonResponse(false, 'Sender Not Found');
        }
        $device_id = $receiver->device_id;
        $notifTitle = $sender->name;
        $notiBody = $request->message;
        $message_type = "chat";
        $notificationType = "chat";
        $otherData = "";
        $sender_id = $request->sender_id;

        if ($device_id != "") {
            
            $this->fcmService->sendNotification($device_id, $notifTitle, $notiBody, $message_type, $otherData, $notificationType, $sender_id);
        }
        return ResponseHelper::jsonResponse(true, 'Notification Send Successfully!');

    }

    public function text_notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $device_id = $user->device_id;
        $title = "Test";
        $body = 'User ' . $user->name . ' has test on your question.';
        $messageType = "voting question";
        $otherData = "voting question";
        $notificationType = "2";
        $this->send_notification($device_id, $title, $body, $messageType, $otherData, $notificationType);

        return ResponseHelper::jsonResponse(true, 'send notification successfully!');
    }

    public function new_text_notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $device_id = $user->device_id;
        $title = "New Test";
        $body = 'User ' . $user->name . ' has new test on your question.';
        $messageType = "voting question";
        $otherData = "voting question";
        $notificationType = "2";

        if ($user->device_id != "") {
            $this->fcmService->sendNotification($user->device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        return ResponseHelper::jsonResponse(true, 'send notification successfully!');
    }

    // send notification
    public function send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type, $question_id = 0)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        // server key
        $serverKey = 'AAAAnAue4jY:APA91bHIxmuujE5JyCVtm9i6rci5o9i3mQpijhqzCCQYUuwLPqwtKSU9q47u3Q2iUDiOaxN7-WMoOH-qChlvSec5rqXW2WthIXaV4lCi4Ps00qmLLFeI-VV8O_hDyqV6OqJRpL1n-k_e';

        $headers = [
            'Content-Type:application/json',
            'Authorization:key=' . $serverKey,
        ];

        // notification content
        $notification = [
            'title' => $notifTitle,
            'body' => $notiBody,
        ];
        // optional
        $dataPayLoad = [
            'to' => '/topics/test',
            'date' => '2019-01-01',
            'other_data' => $other_data,
            'message_Type' => $message_type,
            'notification_type' => $notification_type,
            'question_id' => $question_id,
        ];

        // create Api body
        $notifbody = [
            'notification' => $notification,
            'data' => $dataPayLoad,
            'time_to_live' => 86400,
            'to' => $device_id,
            // 'registration_ids' => $arr,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notifbody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);
    }
}
