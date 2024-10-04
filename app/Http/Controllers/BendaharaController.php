<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class BendaharaController extends Controller
{
    public function bendahara(Request $request)
    {
        return view('halaman.user');
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $user->forceDelete(); // Menghapus data secara permanen
                return redirect('/user')->with('success', 'Data berhasil dihapus secara permanen');
            } else {
                return redirect('/user')->with('error', 'Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect('/user')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

    public function add_user()
    {

        $roles = Role::all();
        return view('tambah.add_user', compact('roles'));
    }

    public function store(Request $request)
    {
        // dd ($request);
        // Validasi input
        $request->validate([
            'name' => ['required', 'min:3', 'max:30', function ($attribute, $value, $fail) {
                // Cek apakah nama sudah ada di database
                if (User::where('name', $value)->exists()) {
                    $fail($attribute . ' sudah terdaftar.');
                }
            }],
            'email' => 'required|unique:users,email',
            'password' => ['required', 'min:8', 'max:12'],
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
            'level' => 'required|array'
        ]);

        // Gunakan DB::transaction untuk menjalankan proses dalam satu transaksi
        DB::begintransaction();
        try {
            // Membuat user baru
            // Menambahkan data pengeluaran baru
            $bendahara = new User();
            $bendahara->name = $request->name;
            $bendahara->email = $request->email;
            $bendahara->password = Hash::make($request->password);
            $bendahara->kelamin = $request->kelamin;
            $bendahara->alamat = $request->alamat;
            $bendahara->save();



            // Menambahkan role ke user
            foreach ($request->level as $role) {
                $bendahara->assignRole($role);
            }

            DB::commit();

            return redirect('/user')->with('success', 'User berhasil ditambahkan.');
        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return redirect('/user')->with('error', 'User gagal ditambahkan! ' . $th->getMessage());
        }
    }


    public function edit($id)
    {
        $roles = Role::all();
        $guruu = User::find($id);

        return view('edit.edit_user', compact('guruu', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $guruu = User::find($id);

        // Validasi input
        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'email' => 'required|email|unique:users,email,' . $guruu->id,
            'password' => ['nullable', 'min:8', 'max:12'],
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        DB::beginTransaction();
        try {
            // Mengupdate data user
            $guruu->name = $request->name;
            $guruu->email = $request->email;
            $guruu->kelamin = $request->kelamin;
            $guruu->alamat = $request->alamat; // Diperbaiki dari 'alamt' ke 'alamat'

            // Menambahkan password ke data hanya jika ada input password
            if ($request->filled('password')) {
                $guruu->password = Hash::make($request->password);
            }

            $guruu->save();

            // Mengupdate role yang dimiliki user
            $guruu->syncRoles($request->roles);

            // Commit transaksi jika tidak ada kesalahan
            DB::commit();

            // Mengalihkan pengguna berdasarkan peran baru mereka
            $redirectPath = $guruu->hasRole('admin') ? '/user' : '/home';

            return redirect($redirectPath)->with('update_success', 'Data user berhasil diperbarui.');
        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Redirect dengan pesan gagal
            return redirect('/user')->with('error', 'User gagal diperbarui! ' . $th->getMessage());
        }
    }

    public function bendaharaimportexcel(Request $request)
    {
        User::query()->where('level', 'bendahara')->delete();
        $file = $request->file('file');
        $namafile = $file->getClientOriginalName();
        $file->move('DataBendahara', $namafile);

        Excel::import(new UserImport, public_path('/DataBendahara/' . $namafile));
        return redirect('/bendahara')->with('success', 'Data Berhasil Ditambahkan');
    }



    public function switchRole(Request $request)
    {
        $user = auth()->user();

        if (!session()->has('activeRole')) {
            if ($user->roles->count() === 1) {
                session(['activeRole' => $user->roles->first()->name]);
            }
        }

        // Simpan role yang dipilih di session
        Session::put('activeRole', $request->role);

        // Cek apakah user memiliki role yang dipilih
        $hasRole = $user->hasRole(Session::get('activeRole'));

        // Jika user memiliki role yang dipilih, atur permissions sesuai dengan role
        if ($hasRole) {
            // Pastikan role aktif di session
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

            // Redirect sesuai dengan role yang dipilih
            $redirectPath = $activeRole === 'admin' ? '/user' : '/home';

            return redirect($redirectPath)->with('success', 'Role dan permissions berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke peran ini.');
        }
    }

    public function showDetail($id)
    {
        $user = User::with('roles')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Pengeluaran not found'], 404);
        }

        return response()->json([
            // 'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'kelamin' => $user->kelamin,
            'alamat' => $user->alamat,

            // Ambil nama kategori
        ]);
    }
}
