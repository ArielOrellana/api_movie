<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request){
        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ],201);
    }

    public function login(LoginRequest $request){
        $credentials=$request->only('email', 'password');

        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'error' => 'invalid credentials'
                ], 400);
            }
        }catch(JWTException $e){
            return response()->json([
                'error' => 'not create token'
            ], 500);
        }

        return response()->json(compact('token'));
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user()
    {
        return response()->json(auth()->user());
    }
}
