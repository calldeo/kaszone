<?php

namespace App\Http\Controllers;

use App\Exports\PemasukanExport;
use App\Imports\PemasukanImport;
use App\Models\Category;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


class PemasukanController extends Controller
{
    public function index(Request $request)
    {

        $year = $request->input('year');

        if ($year) {
            $pemasukan = Pemasukan::whereYear('date', $year)->get();
            $totalPemasukan = $pemasukan->sum('jumlah');
        } else {
            $pemasukan = Pemasukan::all();
        }



        return view('halaman.datapemasukan', compact('pemasukan', 'year'));
    }

    public function create()
    {
        // $categories = Category::all(); // Mengambil semua kategori
        // $categories = Category::where('jenis_kategori', '1')->get();
        return view('tambah.add_pemasukan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            //code... 
            $pemasukan = new Pemasukan();
            $pemasukan->name = $request->name;
            $pemasukan->description = $request->description;
            $pemasukan->date = $request->date;
            $pemasukan->jumlah = $request->jumlah;
            $pemasukan->id = $request->category_id;



            // dd($pemasukan);
            $pemasukan->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/pemasukan')->with('success', 'Pemasukan gagal ditambahkan!' . $th->getMessage());

            //throw $th;
        }
        return redirect('/pemasukan')->with('success', 'Pemasukan berhasil ditambahkan!');

        // Pemasukan::create($request->all());

    }

    public function destroy($id_data)
    { {
            try {
                $pemasukan = Pemasukan::find($id_data);

                if ($pemasukan) {
                    $pemasukan->forcedelete(); // Use delete() for soft deletes or forceDelete() if you need permanent deletion
                    return redirect('/pemasukan')->with('success', 'Data berhasil dihapus.');
                } else {
                    return redirect('/pemasukan')->with('error', 'Data tidak ditemukan.');
                }
            } catch (\Exception $e) {
                return redirect('/pemasukan')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
            }
        }
    }


    // public function edit($id_data)
    // {
    //     $pemasukan = Pemasukan::findOrFail($id_data);
    //     $categories = Category::where('jenis_kategori', '1')->get();

    //     return view('edit.edit_pemasukan', compact('pemasukan', 'categories'));
    // }

    // public function update(Request $request, $id_data)
    // {
    //     // Validate input
    //     $request->validate([
    //         'name' => ['required', 'min:3', 'max:30'],
    //         'description' => ['required', 'min:3', 'max:255'],
    //         'date' => ['required', 'date'],
    //         'jumlah' => 'required|numeric|min:0',
    //         'category_id' => ['nullable', 'exists:categories,id'],
    //     ]);

    //     // Start database transaction
    //     DB::beginTransaction();

    //     try {
    //         // Find the pemasukan data by ID
    //         $pemasukan = Pemasukan::findOrFail($id_data);

    //         // Update pemasukan data
    //         $pemasukan->name = $request->name;
    //         $pemasukan->description = $request->description;
    //         $pemasukan->date = $request->date;
    //         $pemasukan->jumlah = $request->jumlah;
    //         $pemasukan->category_id = $request->category_id ?? $pemasukan->category_id; // Use existing category if not provided

    //         // Save changes
    //         $pemasukan->save();

    //         // Commit transaction
    //         DB::commit();

    //         // Redirect with success message
    //         return redirect('/pemasukan')->with('update_success', 'Data pemasukan berhasil diperbarui.');
    //     } catch (\Throwable $th) {
    //         // Rollback transaction on error
    //         DB::rollback();

    //         // Redirect with error message
    //         return redirect('/pemasukan')->with('error', 'Pemasukan gagal diperbarui! ' . $th->getMessage());
    //     }
    // }

    public function edit($id_data)
    {
        $pemasukan = Pemasukan::findOrFail($id_data);
        $categories = Category::where('jenis_kategori', 'pemasukan')->get();

        return view('edit.edit_pemasukan', compact('id_data', 'pemasukan', 'categories'));
    }

    public function update(Request $request, $id_data)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => ['required', 'date'],
            'jumlah' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $pemasukan = Pemasukan::findOrFail($id_data);
            $pemasukan->name = $request->input('name');
            $pemasukan->description = $request->input('description');
            $pemasukan->date = $request->input('date');
            $pemasukan->jumlah = $request->input('jumlah');
            $pemasukan->id = $request->input('category_id');

            $pemasukan->save();

            // Commit transaksi
            DB::commit();

            return redirect('/pemasukan')->with('success', 'Pemasukan updated successfully');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollBack();

            return redirect('/pemasukan')->with('error', 'Terjadi kesalahan saat mengimpor data' . $e->getMessage());
        }
    }



    // Method untuk mendapatkan detail kategori
    public function showDetail($id_data)
    {
        $pemasukan = Pemasukan::with('category')->find($id_data);

        if (!$pemasukan) {
            return response()->json(['message' => 'Pengeluaran not found'], 404);
        }

        return response()->json([
            'id_data' => $pemasukan->id,
            'name' => $pemasukan->name,
            'description' => $pemasukan->description,
            'date' => $pemasukan->date,
            'jumlah' => $pemasukan->jumlah,
            'category_name' => $pemasukan->category->name, // Ambil nama kategori
        ]);
    }

    public function pemasukanImportExcel(Request $request)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Validasi file
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048', // Sesuaikan dengan format yang diizinkan
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
            @unlink(public_path('DataPemasukan/' . $namafile)); // Menghapus file dari server

            return redirect('/pemasukan')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error jika diperlukan
            \Log::error('Import Pemasukan failed: ' . $e->getMessage());

            return redirect('/pemasukan')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        // Path ke template Excel untuk pemasukan
        $pathToFile = public_path('templates/template_pemasukan.xlsx');

        return response()->download($pathToFile);
    }
    public function exportPemasukanPDF(Request $request)
    {
        $year = $request->input('year');

        if ($year) {
            $pemasukan = Pemasukan::whereYear('date', $year)->get();
        } else {
            $pemasukan = Pemasukan::all();
        }

        $pdf = PDF::loadView('pemasukan.pdf', compact('pemasukan', 'year'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream($year ? "pemasukan_$year.pdf" : "pemasukan_seluruh.pdf");
    }

    public function exportPemasukanExcel(Request $request)
    {
        $year = $request->input('year');

        if ($year) {
            $pemasukan = Pemasukan::whereYear('date', $year)->get();
        } else {
            $pemasukan = Pemasukan::all();
        }

        return Excel::download(new PemasukanExport($pemasukan,  $year), $year ? "pemasukan_$year.xlsx" : "pemasukan_seluruh.xlsx");
    }

    public function getCategories($jenisKategori)
    {
        // dd($jenisKategori);

        $options = Category::where('jenis_kategori', $jenisKategori)->get();


        $formattedOptions = $options->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });


        return response()->json($formattedOptions);
    }
}
