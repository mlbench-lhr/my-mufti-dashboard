<?php
namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequests;
use App\Http\Requests\VerifyOTPRequest;
use App\Mail\GenerateOTPMail;
use App\Models\Interest;
use App\Models\Mufti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPassword extends Controller
{
    public function generate_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $otp_code = mt_rand(1000, 9999);
        // $user = User::where('email', $request->email)->update(['email_code' => $otp_code]);

        $check_email = User::where('email', $request->email)
            ->where(function ($query) {
                $query->where('a_code', '=', '')
                    ->where('g_code', '=', '');
            })->first();

        // $user = Admin::where('email', $request->email)->update(['email_code' => $otp_code]);
        if ($check_email) {

            $user = User::where('email', $request->email)->update(['email_code' => $otp_code]);

            $main_data = ['message' => $otp_code];
            dd(Mail::to($request->email)->send(new GenerateOTPMail($main_data)));
            
            Mail::to($request->email)->send(new GenerateOTPMail($main_data));
            $data = [
                'status'   => true,
                'message'  => 'OTP sent Successfully',
                'OTP Code' => $otp_code,
            ];
            return response()->json($data, 200);
        } else {
            return ResponseHelper::jsonResponse(false, 'Email not founded in Database');
        }
    }
    public function verify_otp(VerifyOTPRequest $request)
    {
        $email      = $request->email;
        $otp        = $request->otp;
        $check_user = User::where([['email', $email], ['email_code', $otp]])->first();
        if ($check_user) {
            User::where('email', $request->email)->update(['email_code' => 0]);
            $user = User::where('email', $email)->first();
            // if ($user->mufti_status == 2) {
            //     $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
            //     $user->interests = $interests;
            // } else {
            //     $user->interests = [];
            // }

            $user->interests = ($user->mufti_status == 2 || $user->mufti_status == 4)
            ? Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get()
            : [];

            $user->reason = ($user->mufti_status == 3 || $user->mufti_status == 6)
            ? Mufti::where('user_id', $user->id)->value('reason') ?? ""
            : "";

            $data = [
                'status'  => true,
                'message' => 'OTP Verified',
                'data'    => $user,
            ];

            return response()->json($data, 200);
        }

        return ResponseHelper::jsonResponse(false, 'Please enter valid OTP');

    }
    public function reset_password(ResetPasswordRequests $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user !== null) {
            $user->password = Hash::make($request->password);
            $user->save();
            return ResponseHelper::jsonResponse(true, 'Reset Password Successfully');

        } else {

            return ResponseHelper::jsonResponse(false, 'User Not Found in Database');

        }
    }
}
