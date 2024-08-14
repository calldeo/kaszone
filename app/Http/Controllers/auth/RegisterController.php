<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function showRegisterForm()
    {
        $roles = Role::all();
        
        return view('auth.register',compact('roles'));
    }

    // Menangani pendaftaran
    public function register(Request $request)
    {
        // Log data input untuk debugging
       

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            // 'level' => 'required|string|in:admin,bendahara', // Validasi level dengan opsi baru
            'alamat' => ['required', 'min:3', 'max:30'],
            'kelamin' => 'required',
            'poto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',



            
        ]);
        
        if ($validator->fails()) {
            return redirect('/register')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Membuat pengguna baru
      $admin =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'level' => $request->level,  // Menambahkan kolom level
            'alamat'=> $request->alamat,
            'kelamin'=> $request->kelamin,

        ]);
        $admin->assignRole($request->level);

        

        // Redirect ke halaman login setelah pendaftaran berhasil
        return redirect('/login')->with('success', 'Pendaftaran berhasil. Silakan masuk.');
    }
}