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
   
    $validator = Validator::make($request->all(), [
        'tanggal' => 'required|date',
        'name' => 'required|array',
        'name.*' => 'required|string|max:255',
        'description' => 'nullable|array',
        'description.*' => 'nullable|string|max:255',
        'jumlah_satuan' => 'required|array',
        'jumlah_satuan.*' => 'required|numeric|min:0',
        'nominal' => 'required|array',
        'nominal.*' => 'required|numeric|min:0',
        'jumlah' => 'required|array',
        'jumlah.*' => 'required|numeric|min:0',
        'dll' => 'nullable|array',
        'dll.*' => 'nullable|numeric',
        'id' => 'required|array',
        'id.*' => 'required|exists:categories,id',
        'image' => 'nullable|array',
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    $parentPengeluaran = ParentPengeluaran::with('pengeluaran')->find($id);
    if (!$parentPengeluaran) {
        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan.'
        ], 404);
    }

    DB::beginTransaction();
    try {
        
        $parentPengeluaran->tanggal = $request->tanggal;
        $parentPengeluaran->save();

        $pengeluaranData = [];

        foreach ($request->name as $key => $name) {
            if (isset($parentPengeluaran->pengeluaran[$key])) {
                $pengeluaran = $parentPengeluaran->pengeluaran[$key];
            } else {
                $pengeluaran = new Pengeluaran();
                $pengeluaran->id_parent = $parentPengeluaran->id;
            }

            $pengeluaran->name = $name;
            $pengeluaran->description = $request->description[$key] ?? null;
            $pengeluaran->jumlah_satuan = $request->jumlah_satuan[$key] ?? 0;
            $pengeluaran->nominal = $request->nominal[$key] ?? 0;
            $pengeluaran->jumlah = $request->jumlah[$key] ?? 0;
            $pengeluaran->dll = $request->dll[$key] ?? 0; 
            $pengeluaran->id = $request->id[$key] ?? null;

            if ($request->hasFile('image.' . $key)) {
                if ($pengeluaran->image) {
                    Storage::disk('public')->delete($pengeluaran->image);
                }
                $pengeluaran->image = $request->file('image.' . $key)->store('pengeluaran_images', 'public');
            }

            $pengeluaran->save();

            $pengeluaranData[] = $pengeluaran;
        }

        
        $existingIds = $parentPengeluaran->pengeluaran->pluck('id')->toArray();
        $requestIds = collect($request->input('id'))->filter()->toArray();
        $idsToDelete = array_diff($existingIds, $requestIds);
        Pengeluaran::destroy($idsToDelete);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
            'data' => [
                'parent_pengeluaran' => $parentPengeluaran,
                'pengeluaran' => $pengeluaranData
            ]
        ], 200);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage() . $e->getLine(),
        ], 500);
    }
}


 public function delete($id_data)
{
    $pengeluaran = Pengeluaran::find($id_data);

    if ($pengeluaran) {
        $sameParentCount = Pengeluaran::where('id_parent', $pengeluaran->id_parent)->count();

        if ($sameParentCount > 1) {
            $pengeluaran->delete();
            return response()->json([
                'status' => 'success', 
                'message' => 'Item berhasil dihapus.'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error', 
                'error' => 'Tidak dapat menghapus item, karena hanya ada 1 data.'
            ], 400);
        }
    }

    return response()->json([
        'status' => 'error',
        'error' => 'Item tidak ditemukan.'
    ], 404);
}

public function deleteAll($id)
{
    $parentPengeluaran = ParentPengeluaran::find($id);

    if ($parentPengeluaran) {
     
        $parentPengeluaran->pengeluaran()->delete();

        
        $parentPengeluaran->delete();

        return response()->json([
            'status' => 'success', 
            'message' => 'Semua pengeluaran berhasil dihapus.'
        ], 200);
    }

    return response()->json([
        'status' => 'error', 
        'error' => 'Data tidak ditemukan.'
    ], 404);
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
