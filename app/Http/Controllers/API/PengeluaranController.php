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
        'tanggal.*' => 'required|date_format:Y-m-d'
    ]);

    DB::beginTransaction();

    try {
        // Buat parent pengeluaran
        $parentPengeluaran = new ParentPengeluaran();
        $parentPengeluaran->tanggal = $request->input('tanggal')[0];
        $parentPengeluaran->save();

        // Array untuk menyimpan semua pengeluaran yang berhasil ditambahkan
        $pengeluaranData = [];

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

            // Tambahkan pengeluaran yang disimpan ke dalam array
            $pengeluaranData[] = $pengeluaran;
        }

        DB::commit();

        // Kembalikan seluruh data pengeluaran yang ditambahkan
        return response()->json([
            'status' => 201,
            'message' => 'Pengeluaran berhasil ditambahkan.',
            'parentPengeluaran' => $parentPengeluaran,
            'pengeluaran' => $pengeluaranData // Mengembalikan semua pengeluaran
        ], 201);
    } catch (\Throwable $th) {
        DB::rollback();
        return response()->json([
            'status' => 'error',
            'message' => 'Pengeluaran gagal ditambahkan! ' . $th->getMessage(),
        ], 500);
    }
}
public function update(Request $request, $id)
{
    // Memastikan tanggal ada di request
    if (!$request->has('tanggal')) {
        return response()->json([
            'status' => 'error',
            'message' => 'Field tanggal diperlukan.'
        ], 400);
    }

    // Validasi input
    $request->validate([
        'tanggal' => 'required|date',
        'name.*' => 'required|string|max:255',
        'description.*' => 'nullable|string|max:255',
        'jumlah_satuan.*' => 'required|numeric|min:0',
        'nominal.*' => 'required|numeric|min:0',
        'jumlah.*' => 'required|numeric|min:0',
        'dll.*' => 'nullable|string|max:255',
        'category_id.*' => 'required|exists:categories,id',
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Temukan ParentPengeluaran
    $parentPengeluaran = ParentPengeluaran::find($id);
    if (!$parentPengeluaran) {
        return response()->json([
            'status' => 'error',
            'message' => 'Data parent pengeluaran tidak ditemukan.'
        ], 404);
    }

    DB::beginTransaction();
    try {
        // Memperbarui tanggal
        $parentPengeluaran->tanggal = $request->tanggal;
        $parentPengeluaran->save();

        $pengeluaranUpdated = [];

        foreach ($request->name as $key => $name) {
            if (isset($parentPengeluaran->pengeluaran[$key])) {
                // Update existing pengeluaran
                $pengeluaran = $parentPengeluaran->pengeluaran[$key];

                // Update data
                $pengeluaran->name = $name;
                $pengeluaran->description = $request->description[$key];
                $pengeluaran->jumlah_satuan = $request->jumlah_satuan[$key];
                $pengeluaran->nominal = $request->nominal[$key];
                $pengeluaran->jumlah = $request->jumlah[$key];
                $pengeluaran->dll = $request->dll[$key] ?? null;

                // Handle image upload
                if ($request->hasFile('image.' . $key)) {
                    if ($pengeluaran->image) {
                        \Storage::disk('public')->delete($pengeluaran->image);
                    }
                    $pengeluaran->image = $request->file('image.' . $key)->store('pengeluaran_images', 'public');
                }

                $pengeluaran->save();
                $pengeluaranUpdated[] = $pengeluaran;
            } else {
                // Create new pengeluaran
                $pengeluaranBaru = new Pengeluaran();
                $pengeluaranBaru->name = $name;
                $pengeluaranBaru->description = $request->description[$key] ?? null;
                $pengeluaranBaru->jumlah_satuan = $request->jumlah_satuan[$key];
                $pengeluaranBaru->nominal = $request->nominal[$key];
                $pengeluaranBaru->jumlah = $request->jumlah[$key];
                $pengeluaranBaru->dll = $request->dll[$key] ?? null;

                if ($request->hasFile('image.' . $key)) {
                    $pengeluaranBaru->image = $request->file('image.' . $key)->store('pengeluaran_images', 'public');
                }

                $parentPengeluaran->pengeluaran()->save($pengeluaranBaru);
                $pengeluaranUpdated[] = $pengeluaranBaru;
            }
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
            'parentPengeluaran' => $parentPengeluaran,
            'pengeluaran' => $pengeluaranUpdated
        ], 200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
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
