<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function user(){
        $user = Auth::user();
        return jsend_success(new UserResource($user));
    }

    public function login(LoginUserRequest $request){
        $email = $request-> input('email');
        $password = $request-> input('password');
        $remember_me =  $request-> input('remember_me');

        try{
            $user = User::whereEmail($email)->first();

            if(is_null($user)){

            }
        }catch (Exception $e){
            Log::error('Login Failed!',[
                'code' => $e->getCode(),
                'trace' => $e-> getTrace()
            ]);
        }

    }
}
