<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function edit()
    {
        $roles = Role::all();
        $user = auth()->user();
        return view('profile.edit', compact('user', 'roles'));
    }

public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'alamat' => 'required|string|max:255',
        'password' => 'nullable|string|min:8',
        'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
    ]);

    DB::beginTransaction();
    try {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->alamat = $request->alamat;

        if ($request->hasFile('foto_profil')) {
            if ($user->poto && Storage::disk('public')->exists($user->poto)) {
                Storage::disk('public')->delete($user->poto);
            }

            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $user->poto = $path;
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        DB::commit();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    } catch (\Throwable $th) {
        DB::rollback();

        return redirect()->route('profile.edit')->with('error', 'Profil gagal diperbarui: ' . $th->getMessage());
    }
}


}