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



        return view('pemasukan.data-pemasukan', compact('pemasukan', 'year'));
    }

    public function create()
    {

        return view('pemasukan.add');
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
        
            $pemasukan = new Pemasukan();
            $pemasukan->name = $request->name;
            $pemasukan->description = $request->description;
            $pemasukan->date = $request->date;
            $pemasukan->jumlah = $request->jumlah;
            $pemasukan->id = $request->category_id;




            $pemasukan->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/pemasukan')->with('success', 'Pemasukan gagal ditambahkan!' . $th->getMessage());

        }
        return redirect('/pemasukan')->with('success', 'Pemasukan berhasil ditambahkan!');

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

    public function edit($id_data)
    {
        $pemasukan = Pemasukan::findOrFail($id_data);
        $categories = Category::where('jenis_kategori', 'pemasukan')->get();

        return view('pemasukan.edit', compact('id_data', 'pemasukan', 'categories'));
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

        
        DB::beginTransaction();

        try {
            $pemasukan = Pemasukan::findOrFail($id_data);
            $pemasukan->name = $request->input('name');
            $pemasukan->description = $request->input('description');
            $pemasukan->date = $request->input('date');
            $pemasukan->jumlah = $request->input('jumlah');
            $pemasukan->id = $request->input('category_id');

            $pemasukan->save();

            
            DB::commit();

            return redirect('/pemasukan')->with('success', 'Pemasukan updated successfully');
        } catch (\Exception $e) {
            
            DB::rollBack();

            return redirect('/pemasukan')->with('error', 'Terjadi kesalahan saat mengimpor data' . $e->getMessage());
        }
    }



    
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
            'category_name' => $pemasukan->category->name, 
        ]);
    }

    public function pemasukanImportExcel(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ]);

            $file = $request->file('file');
            $namafile = $file->getClientOriginalName();
            $file->move(public_path('DataPemasukan'), $namafile);

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(public_path('DataPemasukan/' . $namafile));
            $worksheet = $spreadsheet->getActiveSheet();

            // Validasi format template
            if ($worksheet->getCell('A2')->getValue() !== 'Nama' ||
                $worksheet->getCell('B2')->getValue() !== 'Deskripsi' ||
                $worksheet->getCell('C2')->getValue() !== 'Tanggal' ||
                $worksheet->getCell('D2')->getValue() !== 'Jumlah (Rp)' ||
                $worksheet->getCell('E2')->getValue() !== 'Kode Kategori') {
                throw new \Exception('Format file tidak sesuai. Pastikan menggunakan template yang benar.');
            }

            // Baca semua data dari baris ke-3 sampai baris terakhir
            $highestRow = $worksheet->getHighestRow();
            
            for($row = 3; $row <= $highestRow; $row++) {
                $nama = $worksheet->getCell('A' . $row)->getValue();
                $deskripsi = $worksheet->getCell('B' . $row)->getValue();
                $tanggal = $worksheet->getCell('C' . $row)->getValue();
                $jumlah = $worksheet->getCell('D' . $row)->getValue();
                $kategori = $worksheet->getCell('E' . $row)->getValue();

                // Skip jika baris kosong
                if(empty($nama) && empty($deskripsi) && empty($tanggal) && empty($jumlah) && empty($kategori)) {
                    continue;
                }

                // Konversi format tanggal Excel ke format MySQL
                try {
                    if (is_numeric($tanggal)) {
                        $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
                    } else {
                        $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    throw new \Exception('Format tanggal tidak valid. Gunakan format DD-MM-YYYY');
                }

                // Simpan ke database
                Pemasukan::create([
                    'name' => $nama,
                    'description' => $deskripsi, 
                    'date' => $tanggal,
                    'jumlah' => $jumlah,
                    'id' => $kategori
                ]);
            }

            DB::commit();
            @unlink(public_path('DataPemasukan/' . $namafile));

            return redirect('/pemasukan')->with('success', 'Data Berhasil Ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if(file_exists(public_path('DataPemasukan/' . $namafile))) {
                @unlink(public_path('DataPemasukan/' . $namafile));
            }

            \Log::error('Import Pemasukan failed: ' . $e->getMessage());

            return redirect('/pemasukan')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }


     public function downloadTemplate()
    {
        
        $spreadsheet = new Spreadsheet();

    
        $incomeSheet = $spreadsheet->getActiveSheet();
        $incomeSheet->setTitle('Pemasukan');

        
        $incomeSheet->setCellValue('A1', 'Import Data Pemasukan');
        $incomeSheet->mergeCells('A1:E1');
        $incomeSheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $incomeSheet->getStyle('A1')->getFont()->setBold(true);
        $incomeSheet->setCellValue('A2', 'Nama');
        $incomeSheet->setCellValue('B2', 'Deskripsi');
        $incomeSheet->setCellValue('C2', 'Tanggal');
        $incomeSheet->setCellValue('D2', 'Jumlah (Rp)');
        $incomeSheet->setCellValue('E2', 'Kode Kategori');
        $incomeSheet->setCellValue('G2', 'Keterangan')->getStyle('G2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
        $incomeSheet->setCellValue('G3', '1. Pengisian data dimulai dari baris ke-3');
        $incomeSheet->setCellValue('G4', '2. Kolom C (Tanggal) menggunakan format DD-MM-YYYY');
        $incomeSheet->setCellValue('G5', '3. Kolom D (Jumlah (Rp)) hanya boleh menginput angka');
        $incomeSheet->setCellValue('G6', '4. Kolom E (Kode Kategori) menggunakan Kode dari sheet Kategori Pemasukan');




        
        $categorySheet = $spreadsheet->createSheet();
        $categorySheet->setTitle('Kategori Pemasukan');

        
        $categorySheet->setCellValue('A1', 'Kode Kategori');
        $categorySheet->setCellValue('B1', 'Nama Kategori');

        
        $categories = Category::where('jenis_kategori', '1')->get();
        
        
        $row = 2; 
        foreach ($categories as $category) {
            $categorySheet->setCellValue('A' . $row, $category->id);  
            $categorySheet->setCellValue('B' . $row, $category->name);  
            $row++;
        }

        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_pemasukan.xlsx';

        
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