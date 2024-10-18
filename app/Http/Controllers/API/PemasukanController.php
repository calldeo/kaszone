<?php

namespace App\Http\Controllers\API;
use PDF;
use App\Models\Category;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Imports\KategoriImport;
use App\Exports\PemasukanExport;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage; // Impor Hash
use App\Imports\PemasukanImport;
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
  public function update(Request $request, $id_data)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => ['required', 'date'],
            'jumlah' => 'required|numeric|min:0',
            'id' => 'nullable|exists:categories,id',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $pemasukan = Pemasukan::findOrFail($id_data);
            $pemasukan->name = $request->input('name');
            $pemasukan->description = $request->input('description');
            $pemasukan->date = $request->input('date');
            $pemasukan->jumlah = $request->input('jumlah');
            $pemasukan->id = $request->input('id'); // Ganti id dengan category_id

            $pemasukan->save();

            // Commit transaksi
            DB::commit();

            return response()->json([
                'status' => '200',
                'message' => 'Pemasukan updated successfully',
                'data' => $pemasukan,
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollBack();

            return response()->json([
                'status' => '500',
                'message' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage(),
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

    
    public function saldo()
    {
        // dd(auth()->user()->getAllPermissions());
        // Hitung saldo yang tersedia
          // Ambil total pemasukan dan total pengeluaran
          try{
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
       
        $saldo = $totalPemasukan - $totalPengeluaran;

            // Mengirim file PDF dengan status 200
               return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data saldo',
                'data' => $totalPemasukan,$totalPengeluaran,$saldo,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengambil data pemasukan',
                'error' => $e->getMessage(),
            ], 500);
        }
      
        
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
            
            // Perbaikan: Import kelas PemasukanImport
            $import = new PemasukanImport;

            Excel::import($import, public_path('DataPemasukan/' . $namafile), null, \Maatwebsite\Excel\Excel::XLSX, [
                'startRow' => 2,
                'onlySheets' => [0]
            ]);

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load(public_path('DataPemasukan/' . $namafile));
            $worksheet = $spreadsheet->getActiveSheet();
            $fileContent = [];

            $headers = [];
            foreach ($worksheet->getRowIterator() as $index => $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cellIndex => $cell) {
                    if ($index === 1) {
                        $headers[$cellIndex] = $cell->getValue();
                    } else {
                        $rowData[$headers[$cellIndex]] = $cell->getValue();
                    }
                }
                if ($index !== 1) {
                    $fileContent[] = $rowData;
                }
            }

            DB::commit();

            @unlink(public_path('DataPemasukan/' . $namafile));

            $fileContent = array_filter($fileContent, function($row) {
                return !empty($row['Nama']);
            });

            return response()->json([
                'status' => 200,
                'message' => 'Data Berhasil Ditambahkan',
                'data' => array_values($fileContent)
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Import Pemasukan failed: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();

            $incomeSheet = $spreadsheet->getActiveSheet();
            $incomeSheet->setTitle('Pemasukan');

            $incomeSheet->setCellValue('A1', 'Nama');
            $incomeSheet->setCellValue('B1', 'Deskripsi');
            $incomeSheet->setCellValue('C1', 'Tanggal');
            $incomeSheet->setCellValue('D1', 'Jumlah');
            $incomeSheet->setCellValue('E1', 'Kode Kategori');

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

            $filePath = storage_path('app/public/' . $fileName);
            $writer->save($filePath);

            return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengunduh template: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportPemasukanPDF(Request $request)
    {
        try {
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

            $content = $pdf->output();

            return response($content)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal membuat PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportPemasukanExcel(Request $request)
    {
        try {
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

            $export = new PemasukanExport($pemasukan, $year, $startDate, $endDate);
            return Excel::download($export, $filename);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal membuat Excel: ' . $e->getMessage()
            ], 500);
        }
    }
}
