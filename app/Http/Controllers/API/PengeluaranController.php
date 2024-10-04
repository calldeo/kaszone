<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Imports\KategoriImport;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage; // Impor Hash
use App\Models\ParentPengeluaran;

class PengeluaranController extends Controller
{
    public function getAllOutcome()
    {
        try {
            $pengeluaran = Pengeluaran::with('category','parentPengeluaran')->paginate(5);
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data pengeluaran',
                'data' => $pengeluaran,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengambil data pengeluaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


      public function create($id)
    {
        
        
    }

 public function store(Request $request)
    {
        // Validasi input
        $request->validate([
          'name.*' => 'required|string|max:255',
    'description.*' => 'nullable|string',
    'jumlah_satuan.*' => 'required|numeric|min:0',
    'nominal.*' => 'required|numeric|min:0',
    'dll.*' => 'required|numeric|min:0',
    'jumlah.*' => 'required|numeric|min:0',
    'id.*' => 'required|exists:categories,id',
    'image.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
    // Validasi format tanggal tanpa memeriksa di database
    'tanggal.*' => 'required|date_format:Y-m-d'
        ]);

        DB::beginTransaction();

        try {
            // Buat parent pengeluaran
            $parentPengeluaran = new ParentPengeluaran();
            $parentPengeluaran->tanggal = $request->input('tanggal')[0];
            $parentPengeluaran->save();

            // Proses data untuk setiap pengeluaran
            foreach ($request->input('name') as $i => $name) {
                $pengeluaran = new Pengeluaran();
                $pengeluaran->name = $name;
                $pengeluaran->description = $request->input('description')[$i] ?? null;
                $pengeluaran->jumlah_satuan = $request->input('jumlah_satuan')[$i];
                $pengeluaran->nominal = $request->input('nominal')[$i];
                $pengeluaran->dll = $request->input('dll')[$i];
                $pengeluaran->jumlah = $request->input('jumlah')[$i];
                $pengeluaran->id = $request->input('id')[$i];

                $pengeluaran->id_parent = $parentPengeluaran->id;

                // Simpan gambar jika ada file yang diupload
                if ($request->hasFile("image.$i")) {
                    $path = $request->file("image.$i")->store('image', 'public');
                    $pengeluaran->image = $path;
                }

                $pengeluaran->save(); 
            }

            DB::commit();
            return response()->json([
                'status' => 201,
                'message' => 'Pengeluaran berhasil ditambahkan.',
                'data' => $parentPengeluaran,
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Pengeluaran gagal ditambahkan! ' . $th->getMessage(),
            ], 500);
        }
    }




     // Metode untuk menampilkan detail pengguna dan peran yang tersedia
    public function show($id_data)
    {
        
    $pengeluaran = Pengeluaran::find($id_data);
    $category = Category::all(); // Mengambil data pengguna berdasarkan ID
        
        // Daftar peran (opsional), sesuaikan jika peran ada

        return response()->json([
            'status' => 200,
            'message' => 'Sukses mengambil data pengeluaran',
            'data' => [
                'category' => $category,
                'pengeluaran' => $pengeluaran,
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
        $pengeluaran = Pengeluaran::find($id_data);

            // Perbarui data pengguna
        $pengeluaran->name = $request->name;
        $pengeluaran->description = $request->description;
        $pengeluaran->date = $request->date;
        $pengeluaran->jumlah = $request->jumlah;
        $pengeluaran->id = $request->id ?? $pengeluaran->id; 



              $pengeluaran->save();
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil mengupdate data pengeluaran $pengeluaran ' ,
                'data' => $pengeluaran,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengupdate data pengeluaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


     public function destroy($id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->forcedelete();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil menghapus pengeluaran',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Pengeluaran tidak ditemukan',
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
        $pengeluaran = Pengeluaran::find($id);

        // Jika kategori ditemukan, kembalikan data kategori
        if ($pengeluaran) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data pengeluaran',
                'data' => $pengeluaran,
            ], 200);
        } else {
            // Jika kategori tidak ditemukan, kembalikan pesan error
            return response()->json([
                'status' => 404,
                'message' => 'pengeluaran tidak ditemukan.',
            ], 404);
        }
    }
}
