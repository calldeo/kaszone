<?php

namespace App\Http\Controllers\API;
use Spatie\Permission\Models\Role;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Impor Hash


class UserController extends Controller
{


    public function dashboard()
    {
        try {
         $totalPemasukan = Pemasukan::sum('jumlah');
        
        $totalPengeluaran = Pengeluaran::sum('jumlah');
       
        $saldo = $totalPemasukan - $totalPengeluaran;
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data dashboard',
                'data' => $totalPengeluaran,$totalPemasukan,$saldo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengambil data dashboard',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getAllUsers()
    {
        try {
            $users = User::with('roles')->paginate(10);

            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data users',
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengambil data users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


      public function create($id)
    {
        $roles = Role::all();
        
    }


  // Menambahkan pengguna baru
    public function store(Request $request)
    {
        // Validasi input
        
        $validate= Validator::make($request->all(),[
            'name' => ['required', 'min:3', 'max:30', function ($attribute, $value, $fail) {
                if (User::where('name', $value)->exists()) {
                    $fail($attribute . ' sudah terdaftar.');
                }
            }],
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'min:8', 'max:12'],
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
            'level' => 'required' // Validasi level role
        ]);

        // dd($validate);
        $errors = $validate->errors();

        if($validate->fails()) {
            return response()->json([
                'status'=> 409,
                'message' => [
                    'name'=>$errors->first('name')?: 'kosong',
                    'email'=>$errors->first('email')?: 'kosong',
                    'password'=>$errors->first('password')?: 'kosong',
                    'kelamin'=>$errors->first('kelamin')?: 'kosong',
                    'alamat'=>$errors->first('alamat')?: 'kosong',
                    'level'=>$errors->first('level')?: 'kosong',
                    
                ]
                ]);
        }
        // Gunakan DB::transaction untuk menjalankan proses dalam satu transaksi
        DB::beginTransaction();
        try {
            // Membuat user baru
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->kelamin = $request->input('kelamin');
            $user->alamat = $request->input('alamat');
            $user->save();

            // Menambahkan role ke user
                    $user->assignRole($request->level);

            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'User berhasil ditambahkan.',
                'data' => new UserResource($user)
            ], 201);

        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'User gagal ditambahkan! ' . $th->getMessage()
            ], 500);
        }
    }





     // Metode untuk menampilkan detail pengguna dan peran yang tersedia
    public function show($id)
    {
        $user = User::findOrFail($id); // Mengambil data pengguna berdasarkan ID
        
        $roles = Role::all();// Daftar peran (opsional), sesuaikan jika peran ada

        return response()->json([
            'status' => 200,
            'message' => 'Sukses mengambil data pengguna',
            'data' => [
                'user' => $user,
                'roles' => $roles, // Ambil peran jika diperlukan
            ],
        ], 200);
    }

    // Metode untuk memperbarui detail pengguna
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed', // Password opsional
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            // Tambahkan aturan validasi lain sesuai kebutuhan
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id); // Cari pengguna berdasarkan ID

            // Perbarui data pengguna
                 $user->name = $request->name;
                $user->email = $request->email;
                $user->kelamin = $request->kelamin;
                $user->alamat = $request->alamat; // Diperbaiki dari 'alamt' ke 'alamat'

                // Menambahkan password ke data hanya jika ada input password
                  if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

        $user->save();

        // Mengupdate role yang dimiliki user
        $user->syncRoles($request->roles);
            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'Berhasil mengupdate data pengguna ' . $user->name,
                'data' => ['user' => $user],
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengupdate data pengguna',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


     public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->forcedelete();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil menghapus pengguna',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Pengguna tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal menghapus pengguna',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function profile()
    {
         $user = auth()->user(); // Mengambil data pengguna berdasarkan ID
        
        // Daftar peran (opsional), sesuaikan jika peran ada

        return response()->json([
            'status' => 200,
            'message' => 'Sukses mengambil data pengguna',
            'data' => [
                'user' => $user,
                 // Ambil peran jika diperlukan
            ],
        ], 200);
    }

 public function updateProfile(Request $request)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'alamat' => 'required|string|max:255',
        'password' => 'nullable|string|min:8',
        'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Tambahkan aturan validasi lain sesuai kebutuhan
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
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

        // Commit transaksi jika tidak ada kesalahan
        DB::commit();


            return response()->json([
                'status' => 201,
                'message' => 'Berhasil mengupdate data pengguna ' . $user->name,
                'data' => ['user' => $user],
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengupdate data pengguna',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

     public function showDetail($id)
    {
        // Mencari kategori berdasarkan ID
        $user = User::with('roles')->find($id);

        // Jika kategori ditemukan, kembalikan data kategori
        if ($user) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data pengguna',
                'data' => $user,
            ], 200);
        } else {
            // Jika kategori tidak ditemukan, kembalikan pesan error
            return response()->json([
                'status' => 404,
                'message' => 'pengguna tidak ditemukan.',
            ], 404);
        }
    }
}