<?php

namespace App\Http\Controllers;

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
        return redirect('home');
    } else {
        return redirect('/login')->withErrors('Username dan Password yang dimasukkan tidak valid');
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
