<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed', // password bisa kosong
            'level' => 'required|string' ,// validasi untuk level
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
        ]);

        // Update profil pengguna
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->alamat = $request->alamat;
        
        // $user->level = $request->level; // Menyimpan level yang dipilih

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Set flash message sukses
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}