<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function landing()
    {
        // Arahkan ke halaman home jika sudah terautentikasi
        if (Auth::check()) {
            return redirect('home');
        }
        return view('sigin.logins');
    }

    public function halamanlogin()
    {
        // Arahkan ke halaman home jika sudah terautentikasi
        if (Auth::check()) {
            return redirect('home');
        }
        return view('sigin.logins');
    }

    public function postlogin(Request $request)
    {
        // Arahkan ke halaman home jika sudah terautentikasi
        if (Auth::check()) {
            return redirect('home');
        }

        Session::flash('email', $request->email);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];


        if (Auth::attempt($infologin)) {
            $user = User::where('email', $request->email)->first();

            Session::put('activeRole', $user->getRoleNames()[0]);

            $activeRole = Session::get('activeRole');

            // Dapatkan permissions yang terkait dengan role aktif
            // $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

            if ($activeRole) {
                // Dapatkan role dengan nama aktif dari database
                $activeRole = \Spatie\Permission\Models\Role::where('name', $activeRole)->first();

                if ($activeRole) {
                    // Dapatkan permissions yang terkait dengan role aktif
                    $permissions = $activeRole->permissions->pluck('name')->toArray();
                } else {
                    // Jika role tidak ditemukan
                    $permissions = [];
                }
            } else {
                // Jika tidak ada role aktif, set permissions ke array kosong
                $permissions = [];
            }

            // Set permissions di session atau sesuai kebutuhan Anda
            Session::put('permissions', $permissions);

            return redirect('home');
        } else {
            // Set a session variable for the login error
            return redirect('/login')->with('login_error', 'Email dan Password yang dimasukkan tidak valid');
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Berhasil Logout');
    }

    // Di LoginController atau tempat login
    public function authenticated(Request $request, $user)
    {
        $activeRole = $user->roles->first()->name; // Atur sesuai dengan cara Anda menyimpan role
        session(['activeRole' => $activeRole]);

        return redirect()->intended($this->redirectPath());
    }
}
