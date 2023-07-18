<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function UserLogin(Request $request){
        $res = User::where($request->input())->count();
        if($res == 1){
            $token = JWTToken::CreateToken($request->input('email'));
            return response()->json(['msg'=>'Login Success', 'data'=>$token]);
        }else{
            return response()->json(['msg'=>'Login Fail', 'data' => 'Unauthorized']);
        }
    }

    function UserRegistration(Request $request){
        return User::create($request->input());
    }

    function SendOTPtoEmail(Request $request){
        $userEmail = $request->input('email');
        $otp = rand(10000, 99999);
        $res = User::where($request->input())->count();
        if ($res == 1){

            Mail::to($userEmail)->send(new OTPEmail($otp));

            User::where($request->input())->update(['otp'=>$otp]);

            return response()->json(['msg'=>'Success','data'=>'OTP send to your email']);
        }else{
            return response()->json(['msg'=>'Fail', 'data' => 'Unauthorized']);
        }
    }

    function OTPVerify(Request $request){
        $res = User::where($request->input())->count();
        if($res == 1){

            User::where($request->input())->update(['otp'=>'0']);

            return response()->json(['msg'=>'Success','data'=>'OTP verified']);
        }else{
            return response()->json(['msg'=>'Fail', 'data' => 'Unauthorized']);
        }
    }

    function SetPassword(Request $request){
        User::where($request->input())->update(['password'=>$request->input('password')]);
        return response()->json(['msg'=>'Success','data'=>'Password Updated']);
    }

    function ProfileUpdate(){

    }
}
