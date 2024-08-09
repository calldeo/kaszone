<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Menangani pendaftaran
    public function register(Request $request)
    {
        // Log data input untuk debugging
        Log::info('Data Registrasi: ', $request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'level' => 'required|string|in:admin,bendahara', // Validasi level dengan opsi baru
            'alamat' => ['required', 'min:3', 'max:30'],
            'kelamin' => 'required',


            
        ]);
        
        if ($validator->fails()) {
            return redirect('/register')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Membuat pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level,  // Menambahkan kolom level
            'alamat'=> $request->alamat,
            'kelamin'=> $request->kelamin,

        ]);

        // Redirect ke halaman login setelah pendaftaran berhasil
        return redirect('/login')->with('success', 'Pendaftaran berhasil. Silakan masuk.');
    }
}