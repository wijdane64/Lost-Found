<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register user + create token
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:30',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'user',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token
        ],201);
    }

    /**
     * Login user + create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if (!Auth::attempt($request->only('email','password'))) {
            return response()->json([
                'message'=>'Email ou password incorrects'
            ],401);
        }

        $user = User::where('email',$request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token
        ]);
    }

    /**
     * Logout user (revoke current token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message'=>'déconnecté avec succès'
        ]);
    }
}
