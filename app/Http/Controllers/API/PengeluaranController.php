<?php

namespace App\Http\Controllers\API;
use PDF;
use App\Models\Category;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Exports\TemplateExport;
use App\Imports\KategoriImport;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use App\Models\ParentPengeluaran;
use App\Exports\PengeluaranExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Impor Hash


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
        'image.*' => 'nullable|mimes:jpg,jpeg,png|max:10240', // Max size of 10 MB
        'tanggal.*' => 'required|date_format:Y-m-d',
    ]);

    Log::info('Request Data:', $request->all());
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
            $pengeluaran->id = $request->input('category_id')[$i];

            $pengeluaran->id_parent = $parentPengeluaran->id;

            // Simpan gambar jika ada file yang diupload
            if ($request->hasFile("image.$i")) {
                // Validasi file gambar sebelum disimpan
                $image = $request->file("image.$i");

                // Periksa apakah file valid
                if ($image->isValid()) {
                    $path = $image->store('images', 'public'); // Pastikan Anda menyimpan di direktori yang benar
                    $pengeluaran->image = $path; // Atur jalur gambar
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'File gambar tidak valid.',
                    ], 422);
                }
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
            'message' => 'Pengeluaran gagal ditambahkan! ' . $th->getMessage().' Line: '.$th->getLine(),
        ], 500);
    }
}

public function update(Request $request, $id)
{
    // return $request;
    $request->validate([
        'tanggal' => 'required|date',
        'name.*' => 'required|string|max:255', 
        'description.*' => 'nullable|string|max:255',
        'jumlah_satuan.*' => 'required|numeric|min:0',
        'nominal.*' => 'required|numeric|min:0',
        'jumlah.*' => 'required|numeric|min:0',
        'dll.*' => 'nullable|string|max:255',
        'category_id.*' => 'required|exists:categories,id',
        'image.*' => 'nullable|mimes:jpg,jpeg,png|max:10240', // Max size of 10 MB
    ]);

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

        foreach ($request->name as $key => $name) {
            if (isset($parentPengeluaran->pengeluaran[$key])) {
                $pengeluaran = $parentPengeluaran->pengeluaran[$key];

                $pengeluaran->name = $name;
                $pengeluaran->description = $request->description[$key];
                $pengeluaran->jumlah_satuan = $request->jumlah_satuan[$key];
                $pengeluaran->nominal = $request->nominal[$key];
                $pengeluaran->jumlah = $request->jumlah[$key];
                $pengeluaran->dll = $request->dll[$key];
                $pengeluaran->id = $request->category_id[$key];

                if ($request->hasFile('image.' . $key)) {
                    if ($pengeluaran->image) {
                        \Storage::disk('public')->delete($pengeluaran->image);
                    }
                    $pengeluaran->image = $request->file('image.' . $key)->store('pengeluaran_images', 'public');
                }

                $pengeluaran->save();
            } else {
                $pengeluaranBaru = new Pengeluaran();
                $pengeluaranBaru->name = $name;
                $pengeluaranBaru->description = $request->description[$key] ?? null;
                $pengeluaranBaru->jumlah_satuan = $request->jumlah_satuan[$key];
                $pengeluaranBaru->nominal = $request->nominal[$key];
                $pengeluaranBaru->jumlah = $request->jumlah[$key];
                $pengeluaranBaru->dll = $request->dll[$key] ?? null;
                $pengeluaranBaru->id = $request->category_id[$key];

                if ($request->hasFile('image.' . $key)) {
                    $pengeluaranBaru->image = $request->file('image.' . $key)->store('pengeluaran_images', 'public');
                }

                $parentPengeluaran->pengeluaran()->save($pengeluaranBaru);
            }
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
            'data' => $parentPengeluaran->load('pengeluaran')
        ], 200);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'status' => 'error', 
            'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage() . ' Line: ' . $e->getLine(),
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

public function exportPengeluaranPDF(Request $request)
{
    try {
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Pengeluaran::query()->with('category', 'parentPengeluaran');

        if ($year) {
            $query->whereHas('parentPengeluaran', function ($q) use ($year) {
                $q->whereYear('tanggal', $year);
            });
        }

        if ($startDate && $endDate) {
            $query->whereHas('parentPengeluaran', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        $pengeluaran = $query->get();

        $totalPengeluaran = $pengeluaran->sum('jumlah');

        $pdf = PDF::loadView('pengeluaran.pdf', compact('pengeluaran', 'totalPengeluaran', 'year', 'startDate', 'endDate'));

        $pdf->setPaper('A4', 'portrait');

        if ($startDate && $endDate) {
            $startDateFormatted = date('d-m-Y', strtotime($startDate));
            $endDateFormatted = date('d-m-Y', strtotime($endDate));
            $filename = "pengeluaran_{$startDateFormatted}_sampai_{$endDateFormatted}.pdf";
        } elseif ($year) {
            $filename = "pengeluaran_tahun_{$year}.pdf";
        } else {
            $filename = "pengeluaran_seluruh.pdf";
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
public function exportPengeluaranExcel(Request $request)
{
    try {
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Pengeluaran::query()->with('category', 'parentPengeluaran');

        if ($year) {
            $query->whereHas('parentPengeluaran', function ($q) use ($year) {
                $q->whereYear('tanggal', $year);
            });
        }

        if ($startDate && $endDate) {
            $query->whereHas('parentPengeluaran', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        $pengeluaran = $query->get();

        if ($startDate && $endDate) {
            $startDateFormatted = date('d-m-Y', strtotime($startDate));
            $endDateFormatted = date('d-m-Y', strtotime($endDate));
            $filename = "laporan_pengeluaran_{$startDateFormatted}_sampai_{$endDateFormatted}.xlsx";
        } elseif ($year) {
            $filename = "laporan_pengeluaran_tahun_{$year}.xlsx";
        } else {
            $filename = "laporan_pengeluaran_seluruh.xlsx";
        }

        return Excel::download(new PengeluaranExport($pengeluaran, $year, $startDate, $endDate), $filename);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Gagal mengekspor data pengeluaran: ' . $e->getMessage()
        ], 500);
    }
}




public function showDetail($id)
{
    try {
        $parentPengeluaran = ParentPengeluaran::with('pengeluaran.category')->findOrFail($id);
        
        return response()->json([
            'status' => 200,
            'message' => 'Detail pengeluaran berhasil diambil',
            'data' => $parentPengeluaran
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Gagal mengambil detail pengeluaran: ' . $e->getMessage()
        ], 500);
    }
}
public function importPengeluaran(Request $request)
{
    Log::alert("Memulai proses import");

    $request->validate([
        'file' => 'required|mimes:xls,xlsx|max:2048',
    ]);

    DB::beginTransaction();

    try {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            $spreadsheet = IOFactory::load($file);
            $sheetNames = $spreadsheet->getSheetNames();

            foreach ($sheetNames as $sheetIndex => $sheetName) {
                try {
                    $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $sheetName);
                } catch (\Exception $e) {
                    Log::error('Format tanggal tidak valid pada sheet: ' . $sheetName);
                    continue; 
                }

                Log::alert('Mengimpor dari sheet: ' . $sheetName);

                $parentPengeluaran = new ParentPengeluaran();
                $parentPengeluaran->tanggal = $tanggal; 
                $parentPengeluaran->save();

                Log::alert($parentPengeluaran);

                $sheet = $spreadsheet->getSheet($sheetIndex);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                  
                    if (empty($rowData[0][0])) {
                        Log::warning('Baris ' . $row . ' kosong atau tidak valid, dilewati.');
                        continue;
                    }

                    $pengeluaran = new Pengeluaran();
                    $pengeluaran->name = $rowData[0][0]; 
                    $pengeluaran->description = $rowData[0][1] ?? null;
                    $pengeluaran->jumlah_satuan = $rowData[0][2] ?? 0; 
                    $pengeluaran->nominal = $rowData[0][3] ?? 0; 
                    $pengeluaran->dll = $rowData[0][4] ?? 0;
                    $pengeluaran->jumlah = ($pengeluaran->jumlah_satuan * $pengeluaran->nominal) + $pengeluaran->dll;
                    $pengeluaran->id = $rowData[0][5] ?? null;
                    $pengeluaran->id_parent = $parentPengeluaran->id;

                    $pengeluaran->save();
                    Log::alert($pengeluaran);
                }
            }
        }
        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Data pengeluaran berhasil diimpor!',
            'data'   => $pengeluaran
        ], 200);
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        DB::rollBack();
        Log::error('Gagal mengimpor: ' . $e->getMessage());
        return response()->json([
            'status' => 500,
            'message' => 'Terjadi kesalahan saat membaca file: ' . $e->getMessage()
        ], 500);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal mengimpor: ' . $e->getMessage());
        return response()->json([
            'status' => 500,
            'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage()
        ], 500);
    }
}


public function downloadTemplateAPI()
{
    try {
        $file = Excel::download(new TemplateExport(), 'template_pengeluaran.xlsx')->getFile();
        $content = file_get_contents($file);
        $base64 = base64_encode($content);
        
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="template_pengeluaran.xlsx"');
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Terjadi kesalahan saat mengunduh template: ' . $e->getMessage()
        ], 500);
    }
}

public function showImage($id)
{
    try {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        if (!$pengeluaran->image) {
            return response()->json([
                'status' => 404,
                'message' => 'Gambar tidak ditemukan'
            ], 404);
        }
        
        $path = storage_path('app/public/' . $pengeluaran->image);
        
        if (!file_exists($path)) {
            return response()->json([
                'status' => 404,
                'message' => 'File gambar tidak ditemukan'
            ], 404);
        }
        
        $file = file_get_contents($path);
        $type = mime_content_type($path);
        
        return response($file)->header('Content-Type', $type);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Terjadi kesalahan saat menampilkan gambar: ' . $e->getMessage()
        ], 500);
    }
}

}