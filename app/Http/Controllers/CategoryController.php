<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Imports\KategoriImport;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PDF;

class CategoryController extends Controller
{
    // Mendapatkan daftar kategori
    public function index()
    {

        $categories = Category::paginate(10); // Menggunakan pagination
        return view('halaman.kategori', compact('categories'));
    }

    public function add_kategori()
    {
        // Meneruskan data ke tampilan
        return view('tambah.add_kategori');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'jenis_kategori' => 'required',
            'description' => ['required', 'min:3', 'max:30'],
        ]);

        DB::beginTransaction(); // Memulai transaksi
        try {
            // Membuat instance Category baru
            $category = new Category();
            $category->name = $request->name;
            $category->jenis_kategori = $request->jenis_kategori;
            $category->description = $request->description;

            // Menyimpan data kategori
            $category->save();

            // Commit transaksi jika tidak ada kesalahan
            DB::commit();

            return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return redirect('/kategori')->with('error', 'Kategori gagal ditambahkan! ' . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = Category::find($id);

            if ($user) {
                $user->forceDelete(); // Menghapus data secara permanen
                return redirect('/kategori')->with('success', 'Data berhasil dihapus secara permanen');
            } else {
                return redirect('/kategori')->with('error', 'Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect('/kategori')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }
    public function edit($id)
    {
        $category = Category::find($id);

        // Cek jika kategori tidak ditemukan
        if (!$category) {
            return redirect('/kategori')->with('error', 'Kategori tidak ditemukan.');
        }

        return view('edit.edit_kategori', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'jenis_kategori' => 'required',
            'description' => ['nullable', 'min:3', 'max:100'],
        ]);

        // Temukan kategori berdasarkan ID
        $category = Category::find($id);

        // Cek jika kategori tidak ditemukan
        if (!$category) {
            return redirect('/kategori')->with('error', 'Kategori tidak ditemukan.');
        }

        DB::beginTransaction(); // Memulai transaksi
        try {
            // Memperbarui data kategori yang ada
            $category->name = $request->name;
            $category->jenis_kategori = $request->jenis_kategori;
            $category->description = $request->description;

            // Menyimpan perubahan kategori
            $category->save();

            // Commit transaksi jika tidak ada kesalahan
            DB::commit();

            return redirect('/kategori')->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            return redirect('/kategori')->with('error', 'Kategori gagal diperbarui! ' . $th->getMessage());
        }
    }



    public function kategoriimportexcel(Request $request)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Validasi file
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048', // Sesuaikan dengan format yang diizinkan
            ]);

            // Hapus semua data lama dari tabel Category

            // Pindahkan file ke folder DataKategori
            $file = $request->file('file');
            $namafile = $file->getClientOriginalName();
            $file->move(public_path('DataKategori'), $namafile);

            // Impor data dari file Excel
            Excel::import(new KategoriImport, public_path('DataKategori/' . $namafile));

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Hapus file setelah impor selesai
            @unlink(public_path('DataKategori/' . $namafile)); // Menghapus file dari server

            return redirect('/kategori')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error jika diperlukan
            \Log::error('Import kategori failed: ' . $e->getMessage());

            return redirect('/kategori')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
    public function cetaklaporan()
    {
        // Dapatkan calon dengan jumlah suara terbanyak

        $category = Category::all();
        $pdf = PDF::loadview('halaman.cetaklaporan', compact('category'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('category.pdf');


        return view('halaman.cetaklaporan', compact('category'));
    }

    // Method untuk mendapatkan detail kategori
    public function showDetail($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json($category);
        } else {
            return response()->json(['message' => 'Kategori tidak ditemukan.'], 404);
        }
    }
    // public function downloadTemplate()
    // {
    //     $filePath = public_path('templates/template-category.xlsx'); // Path ke file template
    //     return response()->download($filePath);
    // }
    
    public function downloadTemplate()
    {
        $pathFile = public_path('templates/template-kategori.xlsx');
        
        if (file_exists($pathFile)) {
            return response()->download($pathFile, 'template-kategori.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="template-kategori.xlsx"'
            ]);
        } else {
            return redirect()->back()->with('error', 'File template tidak ditemukan.');
        }
    }
}
