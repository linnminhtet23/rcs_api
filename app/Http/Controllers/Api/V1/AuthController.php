<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Utils\ErrorType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;


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

            if (is_null($user)) {
                return jsend_fail(['message' => 'User does not exists.'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            if (!Auth::attempt(['email' => $email, 'password' => $password])) {
                return jsend_fail(['message' => 'Invalid Credentials.'], JsonResponse::HTTP_UNAUTHORIZED);
            }

            $user = Auth::user();
            
            $tokenResult = $user->createToken('IO Token');
            $access_token = $tokenResult->accessToken;
            $expiration = $tokenResult->token->expires_at->diffInSeconds(now());

            return jsend_success([
                'username' => $user->name,
                'email' => $user->email,
                'token_type' => 'Bearer',
                'access_token' => $access_token,
                'expires_in' => $expiration
            ]);
        }catch (Exception $e){
            Log::error('Login Failed!',[
                'code' => $e->getCode(),
                'trace' => $e-> getTrace()
            ]);
        }

    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if ($request->has('role')) {
                $user->role = $request->input('role');
            }
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return jsend_success(new UserResource($user), JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            return jsend_error(__('api.saved-failed', ['model' => 'User']), $e->getCode(), ErrorType::SAVE_ERROR, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
