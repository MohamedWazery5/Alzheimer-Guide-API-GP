<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password'=>$request->password
        ];
        // if email or password not correct
        if (!$token = auth()->attempt($data)) {
            return responseJson(401, '', 'Email Or Password Not Correct');
        }
        $user = auth()->user();
        //create user token
        $this->createNewToken($token);
        //check if user is caregiver
        if ($user->type == 1) {
            return responseJson(200, array_merge(caregiverData($user), ['token' => $token]), "Caregiver logged in success");
            //check if user is patient
        } else if ($user->type == 0) {
            return responseJson(200, array_merge(patientData($user), ['token' => $token]), " Patient logged in success");
        }
    }

    public function logout()
    {
        auth()->logout();
        return responseJson(200, '', 'User successfully signed out');
    }

    public function refresh()
    {
        return $this->createNewToken(JWTAuth::refresh());
    }

    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
