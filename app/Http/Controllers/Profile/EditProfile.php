<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookAppointment;
use App\Models\HelpFeedBack;
use App\Models\Interest;
use App\Models\MuftiAppointment;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EditProfile extends Controller
{
    // get user profile
    public function my_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::find($request->user_id);
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        if ($user->mufti_status == 2) {

            $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
            $user->interests = $interests;

        } else {
            $user->interests = [];
        }

        return response()->json(
            [
                'status' => true,
                'message' => 'User Fetched Successfully',
                'data' => $user,
            ],
            200
        );
    }
    // update/add user profile image
    public function edit_profile_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'image' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        // decode the base64 image
        $base64File = request('image');

        // store orignal image
        $fileData = base64_decode($base64File);

        $name = 'users_profile/' . Str::random(15) . '.png';

        Storage::put('public/' . $name, $fileData);

        // update the user's profile_pic
        $user->image = $name;
        $user->save();
        $user = User::find($request->user_id);
        if ($user->mufti_status == 2) {
            $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
            $user->interests = $interests;
        } else {
            $user->interests = [];
        }

        //return a response as json assuming you are building a restful API
        return response()->json(
            [
                'status' => true,
                'message' => 'Profile picture updated',
                'data' => $user,
            ],
            200
        );
    }
    // update user password
    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $check_password = Hash::check($request->old_password, $user->password);
        if ($check_password) {
            // update the user password in database.
            $check_new_password = Hash::check($request->new_password, $user->password);
            if ($check_new_password) {
                return ResponseHelper::jsonResponse(false, 'New password must be diffrent from old password');
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            $user = User::find($request->user_id);
            if ($user->mufti_status == 2) {
                $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
                $user->interests = $interests;
            } else {
                $user->interests = [];
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Password updated successfully',
                    'data' => $user,
                ],
                200
            );

        } else {
            return ResponseHelper::jsonResponse(false, 'Old Password does not match');
        }
    }
    // update user name
    public function update_username(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        User::where('id', $request->user_id)->update(['name' => $request->name]);

        $user = User::find($request->user_id);
        if ($user->mufti_status == 2) {
            $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
            $user->interests = $interests;
        } else {
            $user->interests = [];
        }
        return response()->json(
            [
                'status' => true,
                'message' => 'Username updated Successfully',
                'data' => $user,
            ],
            200
        );

    }
    // help & feedback
    public function help_feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'email' => 'required',
            'description' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $data = [
            'user_id' => $request->user_id,
            'email' => $request->email,
            'description' => $request->description,
        ];
        $user = HelpFeedBack::create($data);

        $user = User::find($request->user_id);
        if ($user->mufti_status == 2) {
            $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
            $user->interests = $interests;
        } else {
            $user->interests = [];
        }
        return response()->json(
            [
                'status' => true,
                'message' => 'Feedback submited Successfully',
                'data' => $user,
            ],
            200
        );

    }
    // get user profile
    public function my_queries(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::find($request->user_id);
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $search = $request->search;

        $page = $request->input('page', 1);
        $perPage = 10;
        $totalPages = ceil(UserQuery::where('user_id', $request->user_id)->count() / $perPage);

        $myQueries = UserQuery::forPage($page, $perPage)
            ->with('all_question.mufti_detail.interests')->where('question', 'LIKE', '%' . $search . '%')->where('user_id', $request->user_id)->get();

        return response()->json(
            [
                'status' => true,
                'message' => 'My All Queries',
                'totalpages' => $totalPages,
                'data' => $myQueries,
            ],
            200
        );
    }
    // get user profile
    public function ask_for_me(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mufti_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::find($request->mufti_id);
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }
        $page = $request->input('page', 1);
        $perPage = 10;
        $totalPages = ceil(UserAllQuery::where('mufti_id', $request->mufti_id)->count() / $perPage);

        $myAllQueries = UserAllQuery::forPage($page, $perPage)->with('user_detail.interests')->where(['mufti_id' => $request->mufti_id])->get();

        return response()->json(
            [
                'status' => true,
                'message' => 'My All Queries',
                'totalpages' => $totalPages,
                'data' => $myAllQueries,
            ],
            200
        );
    }
    // get user profile
    public function question_accept_decline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required',
            'status' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $question = UserAllQuery::find($request->question_id);
        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }
        $mufti = User::where('id', $question->mufti_id)->first();
        if ($request->status == 1) {
            $user = User::where('id', $question->user_id)->first();

            $device_id = $user->device_id;
            $notifTitle = "Question Request Update";

            $notiBody = 'Your request for private question to Mufti ' . $mufti->name . ' has been accepted.';
            $body = 'Your request for private question to Mufti ' . $mufti->name . ' has been accepted.';
            $message_type = "Question Accepted";

            $this->send_notification($device_id, $notifTitle, $notiBody, $message_type);

            $data = [
                'user_id' => $user->id,
                'title' => $notifTitle,
                'body' => $body,
            ];
            Notification::create($data);

        }
        if ($request->status == 2) {
            $user = User::where('id', $question->user_id)->first();

            $device_id = $user->device_id;
            $notifTitle = "Question Request Update";
            $notiBody = 'Your request for private question to Mufti ' . $mufti->name . ' has been rejected.';
            $body = 'Your request for private question to Mufti ' . $mufti->name . ' has been rejected.';
            $message_type = "Question Rejected";

            $this->send_notification($device_id, $notifTitle, $notiBody, $message_type);

            $data = [
                'user_id' => $user->id,
                'title' => $notifTitle,
                'body' => $body,
            ];
            Notification::create($data);

        }

        UserAllQuery::where('id', $request->question_id)->update(['status' => $request->status]);

        return ResponseHelper::jsonResponse(true, 'Question Status Updated');
    }
    // get user profile
    public function my_all_queries(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'query_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::find($request->user_id);
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $query = UserQuery::find($request->query_id);
        if (!$query) {
            return ResponseHelper::jsonResponse(false, 'Query Not Found');
        }

        $myAllQueries = UserAllQuery::with('mufti_detail.interests')->where(['user_id' => $request->user_id, 'query_id' => $request->query_id])->get();

        return response()->json(
            [
                'status' => true,
                'message' => 'My All Queries',
                'data' => $myAllQueries,
            ],
            200
        );
    }
    // delete user account
    public function delete_account(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $user->delete();

        return ResponseHelper::jsonResponse(true, 'User deleted Successfully!');
    }

    public function book_an_appointment(BookAppointment $request)
    {

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $mufti = User::where(['id' => $request->mufti_id, 'mufti_status' => 2])->first();

        if (!$mufti) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }

        $data = [
            'user_id' => $request->user_id,
            'mufti_id' => $request->mufti_id,
            'category' => $request->category,
            'description' => $request->description,
            'date' => $request->date,
            'duration' => $request->duration,
            'payment_id' => $request->payment_id ?? "",
            'payment_method' => $request->payment_method ?? "",
            'consultation_fee' => $request->consultation_fee,
        ];

        $appointment = MuftiAppointment::create($data);
        $user_id = $user->id;
        $message = "A new appointment booked by " . $user->name;
        $type = "booked appointment";

        ActivityHelper::store_avtivity($user_id, $message, $type);

        return ResponseHelper::jsonResponse(true, 'Book Appointment successfully!');
    }
    public function my_appointments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::where(['id' => $request->user_id])->first();
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $userType = $user->user_type;
        $status = $user->mufti_status;
        // scholar

        if (($userType == "user" && $status != 2) || ($userType == "scholar" && $status != 2)) {
            $appointments = MuftiAppointment::with('mufti_detail')
                ->where('user_id', $request->user_id)
                ->get();
        } elseif ($userType == "scholar" && $status == 2) {
            $appointments = MuftiAppointment::with('user_detail')
                ->where('mufti_id', $request->user_id)
                ->get();
        }
        $appointments = $appointments->map(function ($appointment) {
            $appointment->setAttribute('user_detail', $appointment->mufti_detail);
            unset($appointment->mufti_detail);
            return $appointment;
        });

        return response()->json(
            [
                'status' => true,
                'message' => 'My All Meetings',
                'data' => $appointments,
            ],
            200
        );
    }
    // send notification
    public function send_notification($device_id, $notifTitle, $notiBody, $message_type)
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
            'other_data' => 'Request Notification',
            'message_Type' => $message_type,
            // 'notification' => $notification,
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
