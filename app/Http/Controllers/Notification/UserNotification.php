<?php

namespace App\Http\Controllers\Notification;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserNotification extends Controller
{
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

        $notifications = Notification::forPage($page, $perPage)->where('user_id', $request->user_id)->get();

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
}
