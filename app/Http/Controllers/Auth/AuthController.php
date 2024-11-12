<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SocialRequest;
use App\Models\Interest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

class AuthController extends Controller
{
    // for user signup
    public function sign_up(RegisterRequest $request)
    {

        $check_email1 = User::where('email', $request->email)
            ->where(function ($query) {
                $query->whereNotNull('a_code')->where('a_code', '!=', '')
                    ->orWhere(function ($query) {
                        $query->whereNotNull('g_code')->where('g_code', '!=', '');
                    });
            })->first();

        if ($check_email1) {

            $check_email = User::where('email', $request->email)
                ->where(function ($query) {
                    $query->where('a_code', '=', '')
                        ->where('g_code', '=', '');
                })->first();

            if ($check_email) {
                $data = [
                    'message' => 'This email is already exist in our database',
                    'status' => false,
                    'data' => null,
                ];
                return response($data, 200);
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'device_id' => $request->device_id ?? "",

            ];
            $user = User::create($data);
            $user_data = User::where('id', $user->id)->first();
            if ($user_data->mufti_status == 2) {
                $interests = Interest::where('user_id', $user_data->id)->select('id', 'user_id', 'interest')->get();
                $user_data->interests = $interests;
            } else {
                $user_data->interests = [];
            }
            $user_id = $user_data->id;
            $message = "A new user has registered on the platform. Review their profile.";
            $type = "register";

            ActivityHelper::store_avtivity($user_id, $message, $type);

            $response = [
                'status' => true,
                'message' => 'Successfully registered!',
                'data' => $user_data,
            ];
            return response()->json($response, 200);
        } else {

            $check_email = User::where('email', $request->email)
                ->where(function ($query) {
                    $query->where('a_code', '=', '')
                        ->where('g_code', '=', '');
                })->first();

            if ($check_email) {
                $data = [
                    'message' => 'This email is already exist in our database',
                    'status' => false,
                    'data' => null,
                ];
                return response($data, 200);
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'device_id' => $request->device_id ?? "",

            ];
            $user = User::create($data);
            $user_data = User::where('id', $user->id)->first();
            if ($user_data->mufti_status == 2) {
                $interests = Interest::where('user_id', $user_data->id)->select('id', 'user_id', 'interest')->get();
                $user_data->interests = $interests;
            } else {
                $user_data->interests = [];
            }
            $user_id = $user_data->id;
            $message = "A new user has registered on the platform. Review their profile.";
            $type = "register";

            ActivityHelper::store_avtivity($user_id, $message, $type);

            $response = [
                'status' => true,
                'message' => 'Successfully registered!',
                'data' => $user_data,
            ];
            return response()->json($response, 200);

        }

    }
    // for user signin
    public function sign_in(LoginRequest $request)
    {
        $device_id = $request->device_id ?? "";

        // $user = User::where('email', $request->email)->first();

        $user = User::where('email', $request->email)
            ->where(function ($query) {
                $query->where('a_code', '=', '')
                    ->where('g_code', '=', '');
            })->first();

        if (empty($user)) {
            return response()->json([
                "status" => false,
                "message" => "Email is not registered",
                "data" => null,
            ], 200);
        } else {
            $password = $user['password'];
            if (!empty($password) && $password != "") {
                if (!Hash::check($request->password, $password)) {
                    return response()->json(
                        [
                            "status" => false,
                            "message" => "Incorrect Password",
                            "data" => null,
                        ], 200);
                } else {
                    $user_data = User::where('id', $user->id)->first();

                    if ($device_id != "") {
                        User::where('device_id', $device_id)->update(['device_id' => '']);
                    }

                    User::where('id', $user->id)->update(['device_id' => $device_id]);

                    $user_data->refresh();

                    if ($user_data->mufti_status == 2) {
                        $user_data->user_type = "scholar";
                        $interests = Interest::where('user_id', $user_data->id)->select('id', 'user_id', 'interest')->get();
                        $user_data->interests = $interests;
                    } else {
                        $user_data->interests = [];
                    }
                    $response = [
                        'status' => true,
                        'message' => 'Successfully logged In!',
                        'data' => $user_data,
                    ];
                    return response()->json($response, 200);

                }
            } else {
                return response()->json(
                    [
                        "status" => false,
                        "message" => "user register through social signup",
                        "data" => null,
                    ], 200);
            }
        }

    }
    // for social login and signup
    public function social_login_signup(SocialRequest $request)
    {
        $social_key = $request->social_key;
        $social_token = $request->social_token;
        $email = $request->email;
        $name = $request->name;
        $device_id = $request->device_id ?? "";

        if ($social_key == 'google') {

            $check_email = User::where('email', $request->email)->first();
            $check_user_social_token = User::where('g_code', $social_token)->first();

            // if ($check_email) {

            //     if ($device_id != "") {
            //         User::where('device_id', $device_id)->update(['device_id' => '']);
            //     }

            //     User::where('email', $request->email)->update(['device_id' => $device_id]);

            //     $check_email->refresh();

            //     // $check_email->update(['device_id' => $request->device_id]);
            //     if ($check_email->mufti_status == 2) {
            //         $check_email->user_type = "scholar";
            //         $interests = Interest::where('user_id', $check_email->id)->select('id', 'user_id', 'interest')->get();
            //         $check_email->interests = $interests;
            //     } else {
            //         $check_email->interests = [];
            //     }

            //     $data = [
            //         'status' => true,
            //         'message' => 'Successfully logged In!',
            //         'data' => $check_email,
            //     ];

            //     return response($data, 200);
            // } else

            if ($check_user_social_token) {

                if ($device_id != "") {
                    User::where('device_id', $device_id)->update(['device_id' => '']);
                }

                User::where('g_code', $social_token)->update(['device_id' => $device_id]);

                $check_user_social_token->refresh();

                // $check_user_social_token->update(['device_id' => $request->device_id]);
                if ($check_user_social_token->mufti_status == 2) {
                    $check_user_social_token->user_type = "scholar";
                    $interests = Interest::where('user_id', $check_user_social_token->id)->select('id', 'user_id', 'interest')->get();
                    $check_user_social_token->interests = $interests;
                } else {
                    $check_user_social_token->interests = [];
                }

                $data = [
                    'status' => true,
                    'message' => 'Successfully logged In!',
                    'data' => $check_user_social_token,
                ];

                return response($data, 200);
            } else {

                $user = new User();
                if (!empty($name)) {
                    $user->name = $name;
                }
                if (!empty($email)) {
                    $user->email = $email;
                }
                if (!empty($device_id)) {
                    $user->device_id = $device_id;
                }
                $user->g_code = $social_token;
                $user->save();
                $id = $user->id;
                $user_data = User::find($id);
                if ($user_data->mufti_status == 2) {
                    $interests = Interest::where('user_id', $user_data->id)->select('id', 'user_id', 'interest')->get();
                    $user_data->interests = $interests;
                } else {
                    $user_data->interests = [];
                }
                $user_id = $user_data->id;
                $message = "A new user has registered on the platform. Review their profile.";
                $type = "register";

                ActivityHelper::store_avtivity($user_id, $message, $type);

                $data = [
                    'status' => true,
                    'message' => 'Successfully registered!',
                    'data' => $user_data,
                ];

                return response($data, 200);
            }
        }
        if ($social_key == 'apple') {
            $check_email = User::where('email', $request->email)->first();
            $check_user_social_token = User::where('a_code', $social_token)->first();

            // if ($check_email) {

            //     if ($device_id != "") {
            //         User::where('device_id', $device_id)->update(['device_id' => '']);
            //     }

            //     User::where('email', $request->email)->update(['device_id' => $device_id]);

            //     $check_email->refresh();

            //     // $check_email->update(['device_id' => $request->device_id]);
            //     if ($check_email->mufti_status == 2) {
            //         $check_email->user_type = "scholar";
            //         $interests = Interest::where('user_id', $check_email->id)->select('id', 'user_id', 'interest')->get();
            //         $check_email->interests = $interests;
            //     } else {
            //         $check_email->interests = [];
            //     }
            //     $data = [
            //         'status' => true,
            //         'message' => 'Successfully logged In!',
            //         'data' => $check_email,
            //     ];

            //     return response($data, 200);
            // } else

            if ($check_user_social_token) {
                // $check_user_social_token->update(['device_id' => $request->device_id]);

                if ($device_id != "") {
                    User::where('device_id', $device_id)->update(['device_id' => '']);
                }

                User::where('a_code', $social_token)->update(['device_id' => $device_id]);

                $check_user_social_token->refresh();

                if ($check_user_social_token->mufti_status == 2) {
                    $check_user_social_token->user_type = "scholar";
                    $interests = Interest::where('user_id', $check_user_social_token->id)->select('id', 'user_id', 'interest')->get();
                    $check_user_social_token->interests = $interests;
                } else {
                    $check_user_social_token->interests = [];
                }

                $data = [
                    'status' => true,
                    'message' => 'Successfully logged In!',
                    'data' => $check_user_social_token,
                ];

                return response($data, 200);
            } else {

                $user = new User();
                if (!empty($name)) {
                    $user->name = $name;
                }
                if (!empty($email)) {
                    $user->email = $email;
                }
                if (!empty($device_id)) {
                    $user->device_id = $device_id;
                }
                $user->a_code = $social_token;
                $user->save();
                $id = $user->id;
                $user_data = User::find($id);
                if ($user_data->mufti_status == 2) {
                    $interests = Interest::where('user_id', $user_data->id)->select('id', 'user_id', 'interest')->get();
                    $user_data->interests = $interests;
                } else {
                    $user_data->interests = [];
                }
                $user_id = $user_data->id;
                $message = "A new user has registered on the platform. Review their profile.";
                $type = "register";

                ActivityHelper::store_avtivity($user_id, $message, $type);

                $data = [
                    'status' => true,
                    'message' => 'Successfully registered!',
                    'data' => $user_data,
                ];
                return response($data, 200);
            }
        }
    }
    // for update device ID
    public function update_device_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
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

        $user = User::where('id', $request->user_id)->first();
        $user->update(['device_id' => $request->device_id]);
        return ResponseHelper::jsonResponse(true, 'DeviceID Updated Successfully');

    }
    // for user logout
    public function logout(Request $request)
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
            return ResponseHelper::jsonResponse(false, 'User  Not Found');
        }

        // dd($questions->count());

        // $questions = UserQuery::get()->unique('question');

        // $questions->each(function ($value) {
        //     UserAllQuery::create([
        //         'query_id' => $value->id,
        //         'user_id' => $value->user_id,
        //         'mufti_id' => 9,
        //         'question' => $value->question,
        //         'status' => 1,
        //     ]);
        // });

        $user->update(['device_id' => '']);

        return ResponseHelper::jsonResponse(true, 'Logout Profile Successfully!');
    }
    // for stripe payment
    public function payment_record_test(Request $request)
    {
        try {

            $amount = $request->amount;

            $stripe = new StripeClient(
                'sk_test_51OllT9JvQSLyY5NBWQanTOK6Lew2h8WBs9hrMtVZSEbK5MUGMQ4cwmxdeGxWrDm0hoBwN5sBtsy990Chuux6MrAX00RCwmgmJa'
            );

            $response = $stripe->paymentIntents->create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            $return = ["success" => true, "message" => "Stripe Checked", "data" => $response];
            return response()->json($return);
        } catch (InvalidRequestException $e) {

            $errorResponse = [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => (object) [],
            ];

            return response()->json($errorResponse, 400);
        }
    }

}
