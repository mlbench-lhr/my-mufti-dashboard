<?php

namespace App\Http\Controllers;

use App\Mail\GenerateOTPMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

    public function dashboard()
    {
        return view('frontend.Dashboard');
    }
    public function login()
    {
        return view('frontend.Login');
    }
    public function forget()
    {
        return view('frontend.ForgetPassword');
    }
    public function reset()
    {
        return view('frontend.ResetPassword');
    }
    public function loginn(Request $request)
    {

        $admin = Admin::where('email', '=', $request->email)->first();

        if ($admin) {
            if ($request->password == $admin->password) {
                $request->session()->put('email', $admin->email);
                $request->session()->put('name', $admin->name);
                $request->session()->put('loginId', $admin->id);
                $admin->save();
                $name = Session::get('name');
                if ($name == 'Data Operator') {
                    $data = array(
                        'response' => 'success1',
                        'message' => "Login successfull",
                    );
                } else {
                    $data = array(
                        'response' => 'success',
                        'message' => "Login successfull",
                    );
                }

            } else {
                $data = array(
                    'response' => 'error',
                    'message' => "invalid",
                );
            }
        } else {
            $data = array(
                'response' => 'error',
                'message' => "invalid",
            );
        }

        echo json_encode($data);
    }
    public function generate_otp(Request $request)
    {

        $otp_code = mt_rand(1000, 9999);
        $admin = Admin::where('email', '=', $request->email)->first();

        if ($admin) {
            Admin::where('email', '=', $request->email)->update(['email_code' => $otp_code]);

            $main_data = ['message' => $otp_code];
            Mail::to($request->email)->send(new GenerateOTPMail($main_data));

            $data = array(
                'response' => 'success',
                'message' => "OTP sent Successfully",
            );
        } else {
            $data = array(
                'response' => 'error',
                'message' => "Email not founded in Database",
            );
        }
        echo json_encode($data);

    }
    public function verify_otp(Request $request)
    {

        $email = $request->email;
        $otp = $request->otp;
        $admin = Admin::where([['email', $email], ['email_code', $otp]])->first();

        if ($admin) {
            Admin::where('email', $request->email)->update(['email_code' => 0]);
            $data = array(
                'response' => 'success',
                'message' => "OTP Verified",
                'email' => $email,

            );
        } else {
            $data = array(
                'response' => 'error',
                'message' => "Please enter valid OTP",
            );
        }
        echo json_encode($data);

    }
    public function reset_password(Request $request)
    {

        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            if ($request->confPassword === $request->password) {
                $admin->password = $request->password;
                $admin->save();
                $data = array(
                    'response' => 'success',
                    'message' => "Password update successfully",
                );
            } else {
                $data = array(
                    'response' => 'passwordError',
                    'message' => "Password and Confirm Password should be same!",
                );
            }

        } else {
            $data = array(
                'response' => 'error',
                'message' => "User Not Found in Database",
            );
        }
        echo json_encode($data);

    }
    public function flush(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }

}