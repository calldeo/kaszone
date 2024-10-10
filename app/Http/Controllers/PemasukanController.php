<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Category;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use App\Exports\PemasukanExport;
use App\Imports\PemasukanImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

            // Impor data hanya dari worksheet pertama
            Excel::import(new PemasukanImport, public_path('DataPemasukan/' . $namafile), null, \Maatwebsite\Excel\Excel::XLSX, [
                'startRow' => 2,
                'onlySheets' => [0] // Hanya impor worksheet pertama
            ]);

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
        // Buat objek spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Worksheet untuk Pemasukan
        $incomeSheet = $spreadsheet->getActiveSheet();
        $incomeSheet->setTitle('Pemasukan');

        // Tambahkan header untuk sheet Pemasukan
        $incomeSheet->setCellValue('A1', 'Nama');
        $incomeSheet->setCellValue('B1', 'Deskripsi');
        $incomeSheet->setCellValue('C1', 'Tanggal');
        $incomeSheet->setCellValue('D1', 'Jumlah');
        $incomeSheet->setCellValue('E1', 'Kode Kategori');

        // Worksheet untuk Kategori Pemasukan
        $categorySheet = $spreadsheet->createSheet();
        $categorySheet->setTitle('Kategori Pemasukan');

        // Tambahkan header untuk sheet Kategori
        $categorySheet->setCellValue('A1', 'Kode Kategori');
        $categorySheet->setCellValue('B1', 'Nama Kategori');

        // Ambil kategori dengan jenis 'pemasukan' dari database
        $categories = Category::where('jenis_kategori', '1')->get();
        
        // Isi data kategori ke dalam sheet Kategori
        $row = 2; // Mulai dari baris ke-2 (setelah header)
        foreach ($categories as $category) {
            $categorySheet->setCellValue('A' . $row, $category->id);  // Kode Kategori
            $categorySheet->setCellValue('B' . $row, $category->name);  // Nama Kategori
            $row++;
        }

        // Buat file Excel dan simpan ke dalam output buffer
        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_pemasukan.xlsx';

        // Set response untuk mendownload file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }


    public function exportPemasukanPDF(Request $request)
    {
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Pemasukan::query();

        if ($year) {
            $query->whereYear('date', $year);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $pemasukan = $query->get();

        $pdf = PDF::loadView('pemasukan.pdf', compact('pemasukan', 'year', 'startDate', 'endDate'));

        $pdf->setPaper('A4', 'portrait');

        if ($startDate && $endDate) {
            $filename = "pemasukan_{$startDate}sampai{$endDate}.pdf";
        } elseif ($year) {
            $filename = "pemasukan_tahun_{$year}.pdf";
        } else {
            $filename = "pemasukan_seluruh.pdf";
        }

        return $pdf->stream($filename);
    }

    public function exportPemasukanExcel(Request $request)
    {
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Pemasukan::query();

        if ($year) {
            $query->whereYear('date', $year);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $pemasukan = $query->get();

        if ($startDate && $endDate) {
            $filename = "pemasukan_{$startDate}sampai{$endDate}.xlsx";
        } elseif ($year) {
            $filename = "pemasukan_tahun_{$year}.xlsx";
        } else {
            $filename = "pemasukan_seluruh.xlsx";
        }

        return Excel::download(new PemasukanExport($pemasukan, $year, $startDate, $endDate), $filename);
    }

    public function getCategories($jenisKategori)
    {
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