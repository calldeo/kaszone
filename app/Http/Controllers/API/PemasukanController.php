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
            $pemasukan = Pemasukan::with('category')->paginate(5);
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
     public function importExcel(Request $request)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Hapus semua data lama dari tabel Pemasukan
            Pemasukan::query()->delete();

            // Validasi file input
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv',
            ]);

            // Pindahkan file ke folder DataPemasukan
            $file = $request->file('file');
            $namafile = $file->getClientOriginalName();
            $file->move(public_path('DataPemasukan'), $namafile);

            // Impor data dari file Excel
            Excel::import(new PemasukanImport, public_path('DataPemasukan/' . $namafile));

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Hapus file setelah impor selesai
            Storage::delete('DataPemasukan/' . $namafile);

            return response()->json([
                'status' => 200,
                'message' => 'Data Berhasil Ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error jika diperlukan
            \Log::error('Import Pemasukan failed: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()
            ], 500);
        }
    }

      public function downloadTemplate()
    {
        // Path ke template Excel untuk pemasukan
        $pathToFile = public_path('templates/template_pemasukan.xlsx');

        return response()->download($pathToFile);
    }


     public function cetakPemasukan()
    {
        try {
            $pemasukan = Pemasukan::all();
            $pdf = Pdf::loadView('halaman.cetak-pemasukan', compact('pemasukan'));
            $pdf->setPaper('A4', 'potrait');

            // Mengirim file PDF dengan status 200
            return response()->stream(
                function () use ($pdf) {
                    echo $pdf->output();
                },
                200, // Status 200 OK
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="pemasukan.pdf"',
                ]
            );
        } catch (Exception $e) {
            // Jika terjadi kesalahan
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat membuat PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

     public function exportPengeluaran()
    {
        try {
            // Mengunduh file Excel dengan nama pengeluaran.xlsx
            return Excel::download(new PengeluaranExport, 'pengeluaran.xlsx');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
