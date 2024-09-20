<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Imports\KategoriImport;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage; // Impor Hash


class CategoryController extends Controller
{
    public function getAllCategory()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data kategori',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengambil data kategori',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


      public function create($id)
    {
        
        
    }


  // Menambahkan pengguna baru
    public function store(Request $request)
    {
        // Validasi input
        
        $validate= Validator::make($request->all(),[
            'name' => ['required', 'min:3', 'max:30'],
            'jenis_kategori' => 'required',
            'description' => ['required', 'min:3', 'max:30'],

       
        ]);

        // dd($validate);
        $errors = $validate->errors();

        if($validate->fails()) {
            return response()->json([
                'status'=> 409,
                'message' => [
                    'name'=>$errors->first('name')?: 'kosong',
                    'description'=>$errors->first('description')?: 'kosong',

                    
                ]
                ]);
        }
        // Gunakan DB::transaction untuk menjalankan proses dalam satu transaksi
        DB::beginTransaction();
        try {
           
            $category = new Category();
             $category->name = $request->name;
             $category->jenis_kategori = $request->jenis_kategori;
             $category->description = $request->description;
            $category->save();

            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'Kategori berhasil ditambahkan.',
                'data' =>  $category
            ], 201);
        

        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Kategori gagal ditambahkan! ' . $th->getMessage()
            ], 500);
        }
    }





     // Metode untuk menampilkan detail pengguna dan peran yang tersedia
    public function show($id)
    {
        $category= Category::find($id); // Mengambil data pengguna berdasarkan ID
        
        // Daftar peran (opsional), sesuaikan jika peran ada

        return response()->json([
            'status' => 200,
            'message' => 'Sukses mengambil data kategori',
            'data' => [
                'category' => $category,
                 // Ambil peran jika diperlukan
            ],
        ], 200);
    }

    // Metode untuk memperbarui detail pengguna
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
           'name' => ['required', 'min:3', 'max:30'],
            'description' => ['required', 'min:3', 'max:30'],
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
              $category = Category::find($id); // Cari pengguna berdasarkan ID

            // Perbarui data pengguna
                   $category->name = $request->name;
                  $category->description = $request->description; // Diperbaiki dari 'alamt' ke 'alamat'



              $category->save();
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil mengupdate data pengguna ' ,
                'data' => $category,
            ], 200);
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
            $category = Category::findOrFail($id);
            $category->forcedelete();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil menghapus kategori',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal menghapus kategori',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


     public function showDetail($id)
    {
        // Mencari kategori berdasarkan ID
        $category = Category::find($id);

        // Jika kategori ditemukan, kembalikan data kategori
        if ($category) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data kategori',
                'data' => $category,
            ], 200);
        } else {
            // Jika kategori tidak ditemukan, kembalikan pesan error
            return response()->json([
                'status' => 404,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }
    }

     public function importExcel(Request $request)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Hapus semua data lama dari tabel Category
            Category::query()->delete();

            // Validasi file input
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv',
            ]);

            // Pindahkan file ke folder DataKategori
            $file = $request->file('file');
            $namafile = $file->getClientOriginalName();
            $file->move(public_path('DataKategori'), $namafile);

            // Impor data dari file Excel
            Excel::import(new KategoriImport, public_path('DataKategori/' . $namafile));

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Hapus file setelah impor selesai
            Storage::delete('DataKategori/' . $namafile);

            return response()->json([
                'status' => 200,
                'message' => 'Data Berhasil Ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error jika diperlukan
            \Log::error('Import kategori failed: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat mengimpor data'
            ], 500);
        }
    }
}
