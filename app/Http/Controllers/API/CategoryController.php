<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Imports\KategoriImport;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage; // Impor Hash
use PDF;


class CategoryController extends Controller
{
    public function getAllCategory()
    {
        try {
            $categories = Category::paginate(10);
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data kategori',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengambil data kategori',
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
            'name' => ['required', 'min:3', 'max:30'],
            'jenis_kategori' => 'required',
            'description' => ['required', 'min:3', 'max:30'],

       
        ]);

        // dd($validate);
        $errors = $validate->errors();

        if($validate->fails()) {
            return response()->json([
                'status'=> 409,
                'message' => [
                    'name'=>$errors->first('name')?: 'kosong',
                    'description'=>$errors->first('description')?: 'kosong',

                    
                ]
                ]);
        }
        // Gunakan DB::transaction untuk menjalankan proses dalam satu transaksi
        DB::beginTransaction();
        try {
           
            $category = new Category();
             $category->name = $request->name;
             $category->jenis_kategori = $request->jenis_kategori;
             $category->description = $request->description;
            $category->save();

            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'Kategori berhasil ditambahkan.',
                'data' =>  $category
            ], 201);
        

        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Kategori gagal ditambahkan! ' . $th->getMessage()
            ], 500);
        }
    }





     // Metode untuk menampilkan detail pengguna dan peran yang tersedia
    public function show($id)
    {
        $category= Category::find($id); // Mengambil data pengguna berdasarkan ID
        
        // Daftar peran (opsional), sesuaikan jika peran ada

        return response()->json([
            'status' => 200,
            'message' => 'Sukses mengambil data kategori',
            'data' => [
                'category' => $category,
                 // Ambil peran jika diperlukan
            ],
        ], 200);
    }

    // Metode untuk memperbarui detail pengguna
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
           'name' => ['required', 'min:3', 'max:30'],
           'jenis_kategori' => 'required',

            'description' => ['required', 'min:3', 'max:30'],
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
              $category = Category::find($id); // Cari pengguna berdasarkan ID

            // Perbarui data pengguna
                   $category->name = $request->name;
                  $category->description = $request->description; // Diperbaiki dari 'alamt' ke 'alamat'
             $category->jenis_kategori = $request->jenis_kategori;




              $category->save();
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil mengupdate data pengguna ' ,
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengupdate data pengguna',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


     public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->forcedelete();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil menghapus kategori',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Kategori tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal menghapus kategori',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


     public function showDetail($id)
    {
        // Mencari kategori berdasarkan ID
        $category = Category::find($id);

        // Jika kategori ditemukan, kembalikan data kategori
        if ($category) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses mengambil data kategori',
                'data' => $category,
            ], 200);
        } else {
            // Jika kategori tidak ditemukan, kembalikan pesan error
            return response()->json([
                'status' => 404,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }
    }
    public function kategoriImportExcel(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ]);

            $file = $request->file('file');
            $namaFile = $file->getClientOriginalName();
            $file->move(public_path('DataKategori'), $namaFile);

            $import = new KategoriImport;
            Excel::import($import, public_path('DataKategori/' . $namaFile), null, \Maatwebsite\Excel\Excel::XLSX, [
                'startRow' => 2,
                'onlySheets' => [0]
            ]);

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load(public_path('DataKategori/' . $namaFile));
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

            @unlink(public_path('DataKategori/' . $namaFile));

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diimpor',
                'data' => $fileContent
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Import kategori gagal: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat mengimpor data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportKategori()
    {
        try {
            $category = Category::all();
            $pdf = PDF::loadview('kategori.export-kategori', compact('category'));
            $pdf->setPaper('A4', 'portrait');
            
            $filename = 'kategori_' . date('YmdHis') . '.pdf';
            $content = $pdf->output();
            
            return response($content)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengekspor data kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadTemplateExcel()
    {
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Data Kategori');
            $sheet->setCellValue('A1', 'Nama');
            $sheet->setCellValue('B1', 'Jenis Kategori');
            $sheet->setCellValue('C1', 'Deskripsi');
            
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('Jenis Kategori');
            $sheet2->setCellValue('A1', 'Kode');
            $sheet2->setCellValue('B1', 'Jenis Kategori');
            $sheet2->setCellValue('A2', '1');
            $sheet2->setCellValue('B2', 'Pemasukan');
            $sheet2->setCellValue('A3', '2');
            $sheet2->setCellValue('B3', 'Pengeluaran');
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'template-category.xlsx';
            $filePath = storage_path('app/public/' . $filename);
            $writer->save($filePath);
            
            return response()->download($filePath, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengunduh template Excel',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
