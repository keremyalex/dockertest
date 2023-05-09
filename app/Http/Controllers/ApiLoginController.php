<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('auth_token')->plainTextToken;

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'foto1_url' => $user->foto1_url,
                'foto2_url' => $user->foto2_url,
            ];

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $userData
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ]);
    }
}
