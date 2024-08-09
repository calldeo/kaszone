<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function edit()
    {
        // Mendapatkan data pengguna yang sedang login
        $user = auth()->user();

        // Menampilkan view edit profil dengan data pengguna
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'alamat' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'poto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->alamat = $request->alamat;

        // Jika ada file foto_profil yang diunggah
        if ($request->hasFile('foto_profil')) {
            // Hapus foto profil lama jika ada
            if ($user->poto) {
                Storage::delete($user->poto);
            }

            // Simpan foto profil baru
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $user->poto = $path;
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Simpan perubahan
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}