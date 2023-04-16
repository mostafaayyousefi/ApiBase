<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PassportAuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,email,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }else{
            $email_code = Str::random(30);
            $user = User::create([
                'name' => $request->email,
                'email' => $request->email,
                'email_code' => $email_code,
                'password' => Hash::make($email_code)
            ]);
            $token = $user->createToken('LaravelAuthApp')->accessToken;
            return response()->json(
                ['message' => 'success' ,
                'token' => $token ],  200);
        }
    }


    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $user = User::where([ ['email',$request->email],   ])->first();
        if($user->email_status=='active'){
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorisedddddddddddddd'], 401);
        }
        }
        if($user->email_status!='active'){
            return response()->json(['error' => 'Unauthorisedddddddddddddd'], 401);
        }

    }

    public function verify_email(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $user = User::where([ ['email',$request->email], ['email_code',$request->code], ])->first();
        if($user){
            $user->update([ 'email_status'=>'active' ]);
            $token = $user->createToken('LaravelAuthApp')->accessToken;
            return response()->json(
                ['message' => 'success' ,
                'token' => $token ],  200);
        }else{
            return response()->json(['error' => 'Unauthorisedddddddddddddd'], 401);
        }
    }


    public function final_register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $user = User::where([ ['email',$request->email], ['email_status','=','active'],   ])->first();
        if($user){
            $user = $user->update([ 'name'=>$request->name , 'password'=> Hash::make($request->password) ]);
            return response()->json(
                [
                    'flag' => '1' ,
                    'message' => 'success' ,
             ],  200);
        }else{
            return response()->json(
                [
                    'flag' => '0' ,
                    'message' => 'error' ,
             ],  401);
        }
    }

    public function unauthentication(){
        return response()->json(['error' => 'unauthentication'], 400);
    }



    public function remember(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $remember_password = Str::random(9);
        $user = User::where([ ['email',$request->email], ['email_status','=','active'],   ])->first();
        if($user){
            $user = $user->update([ 'remember_password'=>$remember_password   ]);
            return response()->json(
                [
                    'flag' => '1' ,
                    'message' => 'success' ,
             ],  200);
        }else{
            return response()->json(
                [
                    'flag' => '0' ,
                    'message' => 'error' ,
             ],  401);
        }
    }

    public function confirm_password(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'remember_password' => 'required',
            'password' => 'required| min:8 |confirmed',
            'password_confirmation' => 'required| min:8'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $remember_password = Str::random(9);
        $user = User::where([ ['email',$request->email], ['remember_password','=',$request->remember_password],   ])->first();
        if($user){
            $user = $user->update([ 'password'=>Hash::make($request->password) , 'remember_password'=>$remember_password   ]);
            return response()->json(
                [
                    'flag' => '1' ,
                    'message' => 'success' ,
             ],  200);
        }else{
            return response()->json(
                [
                    'flag' => '0' ,
                    'message' => 'error' ,
             ],  401);
        }
    }

}
