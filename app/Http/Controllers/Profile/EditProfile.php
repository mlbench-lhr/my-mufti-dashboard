<?php
namespace App\Http\Controllers\Profile;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookAppointment;
use App\Models\DeleteAccountRequest;
use App\Models\HelpFeedBack;
use App\Models\Interest;
use App\Models\Mufti;
use App\Models\MuftiAppointment;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use App\Services\FcmService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EditProfile extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
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
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $rejectionReason = "";
        if ($user->mufti_status == 3) {
            $mufti           = Mufti::where('user_id', $user->id)->first();
            $rejectionReason = $mufti ? $mufti->reason : "";
        }

        $user->interests = ($user->mufti_status == 2 || $user->mufti_status == 4)
        ? Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get()
        : [];

        $userArray = $user->toArray();

        $keys      = array_keys($userArray);
        $index     = array_search('mufti_status', $keys) + 1;
        $userArray = array_merge(
            array_slice($userArray, 0, $index),
            ['reason' => $rejectionReason],
            array_slice($userArray, $index)
        );

        return response()->json(
            [
                'status'  => true,
                'message' => 'User Fetched Successfully',
                'data'    => $userArray,
            ],
            200
        );
    }

    // update/add user profile image
    public function edit_profile_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'image'   => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::find($request->user_id);

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        try {
            $base64File = request('image');
            $fileData   = base64_decode($base64File);
            $name       = 'users_profile/' . Str::random(15) . '.png';
            Storage::put('public/' . $name, $fileData);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'An error occurred while uploading the image: ' . $e->getMessage());
        }
        // update the user's profile_pic
        $user->image = $name;
        $user->save();
        $user = User::find($request->user_id);

        $user->reason = ($user->mufti_status == 3)
        ? Mufti::where('user_id', $user->id)->value('reason') ?? ""
        : "";

        if ($user->mufti_status == 2 || $user->mufti_status == 4) {
            $interests       = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
            $user->interests = $interests;

            $muftiId = $user->id;
            $message = "Mufti " . $user->name . " has edited his profile.";
            $type    = "edited profile";

            ActivityHelper::store_avtivity($muftiId, $message, $type);
        } else {
            $user->interests = [];
        }

        //return a response as json assuming you are building a restful API
        return response()->json(
            [
                'status'  => true,
                'message' => 'Profile picture updated',
                'data'    => $user,
            ],
            200
        );
    }
    // update user password
    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'      => 'required',
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (! $user) {
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

            // if ($user->mufti_status == 2) {
            //     $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
            //     $user->interests = $interests;
            // } else {
            //     $user->interests = [];
            // }

            $user->reason = ($user->mufti_status == 3)
            ? Mufti::where('user_id', $user->id)->value('reason') ?? ""
            : "";

            $user->interests = ($user->mufti_status == 2 || $user->mufti_status == 4)
            ? Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get()
            : [];
            return response()->json(
                [
                    'status'  => true,
                    'message' => 'Password updated successfully',
                    'data'    => $user,
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
            'name'    => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        User::where('id', $request->user_id)->update(['name' => $request->name]);

        $user = User::find($request->user_id);
        // if ($user->mufti_status == 2) {
        //     $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
        //     $user->interests = $interests;
        // } else {
        //     $user->interests = [];
        // }

        $user->reason = ($user->mufti_status == 3)
        ? Mufti::where('user_id', $user->id)->value('reason') ?? ""
        : "";

        $user->interests = ($user->mufti_status == 2 || $user->mufti_status == 4)
        ? Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get()
        : [];

        return response()->json(
            [
                'status'  => true,
                'message' => 'Username updated Successfully',
                'data'    => $user,
            ],
            200
        );
    }
    // help & feedback
    public function help_feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required',
            'email'       => 'required',
            'description' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $data = [
            'user_id'     => $request->user_id,
            'email'       => $request->email,
            'description' => $request->description,
        ];
        $user = HelpFeedBack::create($data);

        $user = User::find($request->user_id);
        // if ($user->mufti_status == 2) {
        //     $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
        //     $user->interests = $interests;
        // } else {
        //     $user->interests = [];
        // }

        $user->reason = ($user->mufti_status == 3)
        ? Mufti::where('user_id', $user->id)->value('reason') ?? ""
        : "";

        $user->interests = ($user->mufti_status == 2 || $user->mufti_status == 4)
        ? Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get()
        : [];

        return response()->json(
            [
                'status'  => true,
                'message' => 'Feedback submited Successfully',
                'data'    => $user,
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
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $page    = $request->input('page', 1);
        $perPage = 10;

        $search = $request->search;

        $query = UserQuery::with('all_question.mufti_detail.interests')
            ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc');

        if (! empty($search)) {
            $query->where('question', 'LIKE', '%' . $search . '%');
        }

        $totalPages = ceil($query->count() / $perPage);
        $myQueries  = $query->forPage($page, $perPage)->get();

        $myQueries->each(function ($userQuery) {
            $userQuery->all_question->each(function ($question) {
                $fiqa           = UserQuery::where('id', $question->query_id)->select('fiqa')->first();
                $question->fiqa = $fiqa ? $fiqa->fiqa : "General";
                if ($question->reason === null) {
                    unset($question->reason);
                }
            });
        });

        // if ($search == "") {

        //     $data = UserQuery::with('all_question.mufti_detail.interests')->where('user_id', $request->user_id)
        //         ->orderBy('created_at', 'desc');

        //     $totalPages = ceil($data->count() / $perPage);
        //     $myQueries = $data->forPage($page, $perPage)->get();
        // } else {
        //     $data = UserQuery::with('all_question.mufti_detail.interests')
        //         ->where('question', 'LIKE', '%' . $search . '%')
        //         ->where('user_id', $request->user_id)
        //         ->orderBy('created_at', 'desc');

        //     $totalPages = ceil($data->count() / $perPage);
        //     $myQueries = $data->forPage($page, $perPage)->get();
        // }

        return response()->json(
            [
                'status'     => true,
                'message'    => 'My All Queries',
                'totalpages' => $totalPages,
                'data'       => $myQueries,
            ],
            200
        );
    }
    // get user profile
    // public function ask_for_me(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'mufti_id' => 'required',
    //     ]);

    //     $validationError = ValidationHelper::handleValidationErrors($validator);
    //     if ($validationError !== null) {
    //         return $validationError;
    //     }
    //     $user = User::find($request->mufti_id);
    //     if (!$user) {
    //         return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
    //     }
    //     $page = $request->input('page', 1);
    //     $perPage = 10;
    //     $search = $request->input('search', '');

    //     $totalPages = ceil(UserAllQuery::where('mufti_id', $request->mufti_id)->count() / $perPage);

    //     $myAllQueries = UserAllQuery::forPage($page, $perPage)->with('user_detail.interests')->where(['mufti_id' => $request->mufti_id])->orderBy('created_at', 'DESC')->get();

    //     $fiqas = UserQuery::whereIn('id', $myAllQueries->pluck('query_id'))->pluck('fiqa', 'id');

    //     $myAllQueries->each(function ($query) use ($fiqas) {
    //         $query->fiqa = $fiqas->get($query->query_id, 'General');
    //         if ($query->reason === null) {
    //             unset($query->reason);
    //         }
    //     });

    //     return response()->json(
    //         [
    //             'status' => true,
    //             'message' => 'My All Queries',
    //             'totalpages' => $totalPages,
    //             'data' => $myAllQueries,
    //         ],
    //         200
    //     );
    // }

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
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }

        $page    = $request->input('page', 1);
        $perPage = 10;
        $search  = $request->input('search', '');

        $query = UserAllQuery::with('user_detail.interests')
            ->where('mufti_id', $request->mufti_id)
            ->orderBy('created_at', 'DESC');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user_detail', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                })->orWhere('question', 'LIKE', '%' . $search . '%');
            });
        }

        $totalPages = ceil($query->count() / $perPage);

        $myAllQueries = $query->forPage($page, $perPage)->get();

        // Get fiqas for the queries
        $fiqas = UserQuery::whereIn('id', $myAllQueries->pluck('query_id'))->pluck('fiqa', 'id');

        $myAllQueries->each(function ($query) use ($fiqas) {
            $query->fiqa = $fiqas->get($query->query_id, 'General');
            if ($query->reason === null) {
                unset($query->reason);
            }
        });

        return response()->json(
            [
                'status'     => true,
                'message'    => 'My All Queries',
                'totalpages' => $totalPages,
                'data'       => $myAllQueries,
            ],
            200
        );
    }

    // get user profile
    public function question_accept_decline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required',
            'status'      => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $question = UserAllQuery::find($request->question_id);
        if (! $question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        if ($request->status != 1 && $request->status != 2) {
            return ResponseHelper::jsonResponse(true, 'Invalid Status!');
        } else {

            $mufti = User::where('id', $question->mufti_id)->first();
            if ($request->status == 1) {
                $user = User::where('id', $question->user_id)->first();

                $device_id = $user->device_id;
                $title     = "Question Request Update";

                $notiBody         = 'Your request for private question to ' . $mufti->name . ' has been accepted.';
                $body             = 'Your request for private question to ' . $mufti->name . ' has been accepted.';
                $messageType      = "Question Request Update";
                $otherData        = "Question Request Update";
                $notificationType = "0";

                if ($device_id != "") {
                    $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
                }

                $muftiId = $mufti->id;
                $message = "Mufti " . $mufti->name . " accepted the private question.";
                $type    = "accept question";

                ActivityHelper::store_avtivity($muftiId, $message, $type);

                $data = [
                    'user_id' => $user->id,
                    'title'   => $title,
                    'body'    => $body,
                ];
                Notification::create($data);

                UserAllQuery::where('id', $request->question_id)->update(['status' => $request->status]);

                return ResponseHelper::jsonResponse(true, 'Question Status Updated');
            }
            if ($request->status == 2) {
                $user = User::where('id', $question->user_id)->first();

                $device_id        = $user->device_id;
                $title            = "Question Request Update";
                $notiBody         = 'Your request for private question to ' . $mufti->name . ' has been rejected. Go and check the reason in your Question Requests.';
                $body             = 'Your request for private question to ' . $mufti->name . ' has been rejected. Go and check the reason in your Question Requests.';
                $messageType      = "Question Request Update";
                $otherData        = "Question Request Update";
                $notificationType = "0";

                if ($device_id != "") {
                    $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
                }

                $muftiId = $mufti->id;
                $message = "Mufti " . $mufti->name . " declined the private question.";
                $type    = "decline question";

                ActivityHelper::store_avtivity($muftiId, $message, $type);

                $data = [
                    'user_id' => $user->id,
                    'title'   => $title,
                    'body'    => $body,
                ];
                Notification::create($data);

                UserAllQuery::where('id', $request->question_id)->update(['status' => $request->status, 'reason' => $request->reason ? $request->reason : ""]);

                return ResponseHelper::jsonResponse(true, 'Question Status Updated');
            }
        }
    }
    // get user profile
    public function my_all_queries(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
            'query_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::find($request->user_id);
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $query = UserQuery::find($request->query_id);
        if (! $query) {
            return ResponseHelper::jsonResponse(false, 'Query Not Found');
        }

        $myAllQueries = UserAllQuery::with('mufti_detail.interests')->where(['user_id' => $request->user_id, 'query_id' => $request->query_id])->get();

        return response()->json(
            [
                'status'  => true,
                'message' => 'My All Queries',
                'data'    => $myAllQueries,
            ],
            200
        );
    }
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

        if ($request->user_id == 9 || $request->user_id == "9") {
            return ResponseHelper::jsonResponse(false, 'You cannot delete default mufti.');
        }

        if ($request->user_id == 24 || $request->user_id == "24") {
            return ResponseHelper::jsonResponse(false, 'You cannot delete default Life Coach.');
        }

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        if ($user) {

            $user->deletion_reason = $request->deletion_reason ?? '';
            $user->save();

            $user_id = $user->id;
            $type    = "added private question";
            $message = "User " . $user->name . " has deleted his account.";
            ActivityHelper::store_avtivity($user_id, $message, $type);

            $user->deleteWithRelated();
            $user->delete();
            return ResponseHelper::jsonResponse(true, 'User deleted Successfully!');
        }
    }
    public function request_for_delete_account(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $data = DeleteAccountRequest::where('user_id', $request->user_id)->first();
        if ($request->flag == 1) {

            return response()->json(
                [

                    'status'  => true,
                    'message' => 'Request status',
                    'data'    => $data,
                ],
                200
            );
        } else {
            $existingRequest = DeleteAccountRequest::where('user_id', $request->user_id)->whereIn('status', [1, 2])->first();

            if ($existingRequest) {
                return ResponseHelper::jsonResponse(false, 'Already requested');
            }

            $requestCreate = DeleteAccountRequest::updateOrCreate(
                ['user_id' => $request->user_id],
                [
                    'reason' => "",
                    'status' => 1,
                ]
            );

            $muftiId = $user->id;
            $message = "Mufti " . $user->name . " requested to delete the account.";
            $type    = "deletion request";
            ActivityHelper::store_avtivity($muftiId, $message, $type);

            $data = DeleteAccountRequest::find($requestCreate->id);

            return response()->json(
                [
                    'status'  => true,
                    'message' => 'Request sent successfully!',
                    'data'    => $data,
                ],
                200
            );
        }
    }

    public function book_an_appointment(BookAppointment $request)
    {

        $user = User::where('id', $request->user_id)->first();
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $muftiTypes = [
            9  => ['status' => 2, 'type' => 'scholar', 'notFoundMessage' => 'Mufti Not Found'],
            24 => ['status' => 4, 'type' => 'lifecoach', 'notFoundMessage' => 'Life Coach Not Found'],
        ];

        if (isset($muftiTypes[$request->mufti_id])) {
            $typeDetails = $muftiTypes[$request->mufti_id];
            $mufti       = User::where(['id' => $request->mufti_id, 'mufti_status' => $typeDetails['status']])->first();
            if (! $mufti) {
                return ResponseHelper::jsonResponse(false, $typeDetails['notFoundMessage']);
            }
        } else {
            return ResponseHelper::jsonResponse(false, 'Invalid Mufti ID');
        }

        $data = [
            'user_id'          => $request->user_id,
            'mufti_id'         => $request->mufti_id,
            'category'         => $request->category,
            'description'      => $request->description,
            'contact_number'   => $request->contact_number ?? "",
            'email'            => $request->email ?? "",
            'date'             => $request->date,
            'duration'         => $request->duration,
            'payment_id'       => $request->payment_id ?? "",
            'payment_method'   => $request->payment_method ?? "",
            'consultation_fee' => $request->consultation_fee,
            'user_type'        => $typeDetails['type'],
        ];
        MuftiAppointment::create($data);

        $device_id        = $mufti->device_id;
        $title            = "New Appointment Request Received";
        $notiBody         = 'You have received a new appointment request from ' . $user->name . '.';
        $body             = 'You have received a new appointment request from ' . $user->name . '.';
        $messageType      = "New Appointment Request Received";
        $otherData        = "New Appointment Request Received";
        $notificationType = "0";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        $data = [
            'user_id' => $mufti->id,
            'title'   => $title,
            'body'    => $body,
        ];
        Notification::create($data);

        $user_id = $user->id;
        $message = "A new appointment booked by " . $user->name;
        $type    = "booked appointment";
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
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $userType = $user->user_type;

        $page    = $request->input('page', 1);
        $perPage = 10;
        $search  = $request->input('search', '');

        switch ($userType) {
            case 'scholar':
                $query = MuftiAppointment::with('user_detail', 'mufti_detail.interests')->where('mufti_id', $request->user_id);
                break;
            case 'lifecoach':
                $query = MuftiAppointment::with('user_detail', 'mufti_detail.interests')->where('mufti_id', $request->user_id);
                break;
            default:
                $query = MuftiAppointment::with('user_detail', 'mufti_detail.interests')->where('user_id', $request->user_id);
                break;
        }

        // if ($userType === 'scholar') {
        //     $query->where('mufti_id', $request->user_id);
        // } elseif ($userType === 'user') {
        //     $query->where('user_id', $request->user_id);
        // }

        $query->orderByRaw("STR_TO_DATE(date, '%Y-%m-%d %H:%i:%s') DESC");

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('category', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('user_detail', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('mufti_detail', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        $totalPages     = ceil($query->count() / $perPage);
        $myAppointments = $query->forPage($page, $perPage)->get();

        if ($myAppointments->isEmpty()) {
            return response()->json(
                [
                    'status'     => false,
                    'message'    => 'No Appointments Found',
                    'totalpages' => 0,
                    'data'       => [],
                ],
                200
            );
        }

        return response()->json(
            [
                'status'     => true,
                'message'    => 'All Appointments',
                'totalpages' => $totalPages,
                'data'       => $myAppointments,
            ],
            200
        );
    }
    public function mark_as_completed(Request $request){
    $validator = Validator::make($request->all(), [
        'appointment_id' => 'required|exists:mufti_appointments,id',
    ]);

    $validationError = ValidationHelper::handleValidationErrors($validator);
    if ($validationError !== null) {
        return $validationError;
    }

    $appointment = MuftiAppointment::find($request->appointment_id);

    if (!$appointment) {
        return ResponseHelper::jsonResponse(false, 'Appointment not found', null);
    }

    $userId = $appointment->mufti_id ?? $appointment->user_id;

    if ($appointment->mufti_id !== $userId && $appointment->user_id !== $userId) {
        return ResponseHelper::jsonResponse(false, 'User not found', null);
    }

    if ($appointment->status == 2) { 
        return ResponseHelper::jsonResponse(false, 'Appointment is already completed', null);
    }

    $appointment->status = 2;
    $appointment->save();

    return ResponseHelper::jsonResponse(true, 'Appointment marked as completed', null);
}
       
}
