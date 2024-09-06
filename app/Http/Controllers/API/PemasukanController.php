<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use App\Imports\KategoriImport;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage; // Impor Hash


class PemasukanController extends Controller
{
    public function getAllIncome()
    {
        try {
            $pemasukan = Pemasukan::with('category')->get();
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data pemasukan',
                'data' => $pemasukan,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengambil data pemasukan',
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
           'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',

       
        ]);

        // dd($validate);
        $errors = $validate->errors();

        if($validate->fails()) {
            return response()->json([
                'status'=> 409,
                'message' => [
                    'name'=>$errors->first('name')?: 'kosong',
                    'description'=>$errors->first('description')?: 'kosong',
                    'date'=>$errors->first('date')?: 'kosong',
                    'jumlah'=>$errors->first('jumlah')?: 'kosong',
                    'category_id'=>$errors->first('category_id')?: 'kosong',



                    
                ]
                ]);
        }
        // Gunakan DB::transaction untuk menjalankan proses dalam satu transaksi
        DB::beginTransaction();
        try {
           
            $pemasukan = new Pemasukan();
        $pemasukan->name = $request->name;
        $pemasukan->description = $request->description;
        $pemasukan->date = $request->date;
        $pemasukan->jumlah = $request->jumlah;
        $pemasukan->id = $request->category_id;
         $pemasukan->save();

            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'Pemasukan berhasil ditambahkan.',
                'data' =>  $pemasukan
            ], 201);
        

        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Pemasukan gagal ditambahkan! ' . $th->getMessage()
            ], 500);
        }
    }





     // Metode untuk menampilkan detail pengguna dan peran yang tersedia
    public function show($id_data)
    {
        
    $pemasukan = Pemasukan::find($id_data);
    $category = Category::all(); // Mengambil data pengguna berdasarkan ID
        
        // Daftar peran (opsional), sesuaikan jika peran ada

        return response()->json([
            'status' => 200,
            'message' => 'Sukses mengambil data pemasukan',
            'data' => [
                'category' => $category,
                'pemasukan' => $pemasukan,
                 // Ambil peran jika diperlukan
            ],
        ], 200);
    }

    // Metode untuk memperbarui detail pengguna
    public function update(Request $request, $id_data)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:30'],
            'description' => ['required', 'min:3', 'max:255'],
            'date' => ['required', 'date'],
            'jumlah' => 'required|numeric|min:0',
            'id' => ['nullable', 'exists:categories,id'],
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
        $pemasukan = Pemasukan::find($id_data);

            // Perbarui data pengguna
        $pemasukan->name = $request->name;
        $pemasukan->description = $request->description;
        $pemasukan->date = $request->date;
        $pemasukan->jumlah = $request->jumlah;
        $pemasukan->id = $request->id ?? $pemasukan->id; 



              $pemasukan->save();
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil mengupdate data pemasukan ' ,
                'data' => $pemasukan,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengupdate data pemasukan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


     public function destroy($id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->forcedelete();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil menghapus pemasukan',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Pemasukan tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal menghapus pengeluaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


     public function showDetail($id)
    {
        // Mencari kategori berdasarkan ID
        $pemasukan = Pemasukan::find($id);

        // Jika kategori ditemukan, kembalikan data kategori
        if ($pemasukan) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data pemasukan',
                'data' => $pemasukan,
            ], 200);
        } else {
            // Jika kategori tidak ditemukan, kembalikan pesan error
            return response()->json([
                'status' => 404,
                'message' => 'Pemasukan tidak ditemukan.',
            ], 404);
        }
    }
}
