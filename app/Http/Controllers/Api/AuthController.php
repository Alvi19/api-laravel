<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = $request->validate([
            'email_or_phone' => 'required', 'password' => 'required'
        ]);

        $success = false;

        if (filter_var($validate['email_or_phone'], FILTER_VALIDATE_EMAIL)) {

            $validate = $request->validate(['email_or_phone' => 'required|email|exists:users,email', 'password' => 'required']);
            $success = Auth::attempt(['email' => $validate['email_or_phone'], 'password' => $validate['password']]);
        } else {

            $validate = $request->validate(['email_or_phone' => 'required|exists:users,phone', 'password' => 'required']);
            $success = Auth::attempt(['phone' => $validate['email_or_phone'], 'password' => $validate['password']]);
        }

        if (!$success) {

            return response()->json([
                'success' => false,
                'message' => 'Login gagal cek email/nomor telepon dan kata sandi Anda',
            ], 401);
        } else {

            $auth = Auth::user();
            $token = $auth->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => [
                    'token' => $token,
                    'user' => $auth
                ]
            ], 200);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
