<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => 409,
                'message' => 'Validasi gagal',
                'errors' => [
                    'email' => $validasi->errors()->first('email'),
                    'password' => $validasi->errors()->first('password'),
                ],
            ], 409);
        }

        try {
            $credential = $request->only('email', 'password');

            if (Auth::attempt($credential)) {
                $user = User::with('roles')->find(Auth::id());
                $token = $user->createToken('token')->accessToken;

                return response()->json([
                    'status' => 200,
                    'message' => 'Login berhasil dilakukan',
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'expire_in' => 1200
                ], 200);
            } else {
                return response()->json([
                    "status" => 409,
                    "message" => "User tidak ditemukan, username dan password tidak cocok"
                ], 409);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Login gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil logout user',
        ], 200);
    }


    
}
