<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SettingWaktu;
use Carbon\Carbon;

class BendaharaController extends Controller
{
    
    public function bendahara(Request $request)
    {

        $users = User::where('level', 'bendahara')->paginate(10);


        // Meneruskan data ke tampilan
        return view('halaman.bendahara', compact('users'));
    }

    


public function destroy($id)
{
    try {
        $user = User::find($id);
        
        if ($user) {
            $user->forceDelete(); // Menghapus data secara permanen
            return redirect('/bendahara')->with('uccess', 'Data berhasil dihapus secara permanen');
        } else {
            return redirect('/bendahara')->with('error', 'Data tidak ditemukan.');
        }
    } catch (\Exception $e) {
        return redirect('/bendahara')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
    }
}



    public function add_bendahara()
    {
        // return view('tambah.add_guruu');
         $settings = SettingWaktu::all();

            $expired = false;
    foreach ($settings as $setting) {
        if (Carbon::now()->greaterThanOrEqualTo($setting->waktu)) {
            $expired = true;
            break;
        }
    }
        // Meneruskan data ke tampilan
        return view('tambah.add_bendahara', compact('expired','settings'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
        'level' => 'required',
        'email' => 'required|unique:users,email',
        'password' => ['required', 'min:8', 'max:12'],
    ]);


        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();
        if ($user) {
            // Jika nama atau email sudah digunakan, tampilkan pesan kesalahan
            return back()->withInput()->with('error', 'Nama atau email sudah digunakan.');
        }

       User::create([
            'name' => $request->name,
            'level' => $request->level,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Redirect dengan pesan sukses
        return redirect('/bendahara')->with('success', 'Data Berhasil Ditambahkan');
   

}


    public function edit($id)
{
    $guruu = User::find($id);
    // Jangan mengirimkan password ke tampilan
    unset($guruu->password);
      $settings = SettingWaktu::all();

            $expired = false;
    foreach ($settings as $setting) {
        if (Carbon::now()->greaterThanOrEqualTo($setting->waktu)) {
            $expired = true;
            break;
        }
    }

    return view('edit.edit_bendahara', compact('settings', 'expired','guruu'));
}

public function update(Request $request, $id)
{
    $guruu = User::find($id);

    $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
        'level' => 'required',
        'email' => 'required|email|unique:users,email,' . $guruu->id,
        'password' => ['nullable', 'min:8', 'max:12'], // Mengubah menjadi nullable
    ]);

    $data = [
        'name' => $request->name,
        'level' => $request->level,
        'email' => $request->email,
    ];

    // Menambahkan password ke data hanya jika ada input password
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
    }

    $guruu->update($data);

    return redirect('/bendahara')->with('update_success', 'Data Berhasil Diupdate');
}

    public function search(Request $request)
    {
        // Dapatkan input pencarian
        $searchTerm = $request->input('search');

        // Lakukan pencarian hanya jika input tidak kosong
        if (!empty($searchTerm)) {
            // Validasi input
            $request->validate([
                'search' => 'string', // Sesuaikan aturan validasi sesuai kebutuhan Anda
            ]);

            // Lakukan pencarian dengan mempertimbangkan validasi input, level 'admin', dan status_pemilihan
            $users = User::where('level', 'guru')
                        ->where(function ($query) use ($searchTerm) {
                            $query->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('status_pemilihan', 'like', "%{$searchTerm}%"); // Ubah sesuai dengan tipe data status_pemilihan
                        })
                        ->get();
        } else {
            // Jika input kosong, ambil semua data user dengan level 'admin'
            $users = User::where('level', 'guru')->get();
        }

        // Memberikan respons berdasarkan hasil pencarian
        return response()->json($users);
    }


    public function bendaharaimportexcel(Request $request) {

        // DB::table('users')->where('level','guru')->delete();
        User::query()->where('level','bendahara')->delete();
        $file=$request->file('file');
        $namafile = $file->getClientOriginalName();
        $file->move('DataBendahara', $namafile);

        Excel::import(new UserImport, public_path('/DataBendahara/'.$namafile));
        return redirect('/bendahara')->with('success', 'Data Berhasil Ditambahkan');
        
    }
}