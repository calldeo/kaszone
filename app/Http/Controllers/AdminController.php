<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\SettingWaktu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\ParentPengeluaran;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{



    public function users(Request $request)
    {
        if ($request->ajax()) {
            $bendahara = User::role(['bendahara', 'admin', 'reader'])
                ->with('roles')
                ->select(['id', 'name', 'email', 'kelamin', 'alamat'])
                ->get();

            return DataTables::of($bendahara)
                ->addIndexColumn()
                ->addColumn('roles', function ($row) {
                    return $row->roles->pluck('name')->implode(', ');
                })
                ->addColumn('opsi', function ($row) {
                    return '
                    <div class="d-flex align-items-center">
                        <form action="/user/' . $row->id . '/edit" method="GET" class="mr-1">
                            <button type="submit" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></button>
                        </form>
                        <form action="/user/' . $row->id . '/destroy" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                ';
                })
                ->rawColumns(['roles', 'opsi'])
                ->make(true);
        }
    }


    public function kategoris(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::select(['id', 'name', 'jenis_kategori', 'description'])->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('jenis_kategori', function ($row) {
                    return $row->jenis_kategori == 1 ? 'pemasukan' : 'pengeluaran';
                })
                ->addColumn('opsi', function ($row) {
                    return '
                    <div class="d-flex align-items-center">
                        <form action="' . route('kategori.edit', $row->id) . '" method="GET" class="mr-1">
                            <button type="submit" class="btn btn-warning btn-xs">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>

                        <form action="/kategori/' . $row->id . '/destroy" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
                })
                ->rawColumns(['opsi'])
                ->make(true);
        }
    }



      public function income(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->input('start_created_at');
            $endDate = $request->input('end_created_at');
            $year = $request->input('year');

            $pemasukan = Pemasukan::with('category')->select(['id_data', 'name', 'description', 'date', 'jumlah', 'id']);

            if ($year) {
                $pemasukan = $pemasukan->whereYear('date', $year);
            }

            if ($startDate != null && $endDate != null) {
                $pemasukan = $pemasukan->whereBetween('date', [$startDate, $endDate]);
            }
            $totalJumlah = $pemasukan->sum('jumlah');
            if ($request->input('total_data') === 'true') {
                $totalJumlah = 'Rp' . number_format($totalJumlah, 0, ',', '.');
                return $totalJumlah;
            }

            return DataTables::of($pemasukan)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category ? $row->category->name : 'Tidak ada kategori';
                })
                ->editColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d-m-Y');
                })
                ->editColumn('jumlah', function ($row) {
                    return 'Rp' . number_format($row->jumlah, 0, ',', '.');
                })
                ->addColumn('opsi', function ($row) {
                    $user = auth()->user();
                    $editButton = '';
                    $deleteButton = '';

                    if (session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara') {
                        $editButton = '<a href="/pemasukan/' . $row->id_data . '/edit" class="btn btn-warning btn-xs mr-1"><i class="fas fa-edit"></i></i></a>';
                        $deleteButton = '<form action="/pemasukan/' . $row->id_data . '/destroy" method="POST" style="display:inline;">' .
                            csrf_field() .
                            method_field('DELETE') .
                            '<button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>' .
                            '</form>';
                    }
                    
                    return '<div class="d-flex align-items-center">' . $editButton . $deleteButton . '</div>';
                })
                ->rawColumns(['opsi'])
                ->make(true);
        }
    }

    public function production(Request $request)
    {
        if ($request->ajax()) {
            $startDate = $request->input('start_created_at');
            $endDate = $request->input('end_created_at');
            $year = $request->input('year');

            $pengeluaran = ParentPengeluaran::with('pengeluaran.category')->select(['id', 'tanggal']);

            if ($year) {
                $pengeluaran = $pengeluaran->whereYear('tanggal', $year);
            }

            if ($startDate != null && $endDate != null) {
                $pengeluaran = $pengeluaran->whereBetween('tanggal', [$startDate, $endDate]);
            }
            
            $totalJumlah = $pengeluaran->withSum('pengeluaran', 'jumlah')->get()->sum('pengeluaran_sum_jumlah');
            
            if ($request->input('total_data') === 'true') {
                $totalJumlah = 'Rp' . number_format($totalJumlah, 0, ',', '.');
                return $totalJumlah;
            }
            
            if ($request->input('search.value') != null) {
                $pengeluaran = $pengeluaran->whereHas('pengeluaran', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('search.value') . '%')
                        ->orWhere('description', 'like', '%' . $request->input('search.value') . '%')
                        ->orWhere('tanggal', 'like', '%' . $request->input('search.value') . '%')
                        ->orWhere('jumlah_satuan', 'like', '%' . $request->input('search.value') . '%')
                        ->orWhere('nominal', 'like', '%' . $request->input('search.value') . '%')
                        ->orWhere('dll', 'like', '%' . $request->input('search.value') . '%');
                });
            }

            return DataTables::of($pengeluaran)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return Carbon::parse($row->tanggal)->format('d-m-Y');
                })
                ->addColumn('name', function ($row) {
                    $nama = '';
                    foreach ($row->pengeluaran as $val) {
                        $nama .= $val->name . '<br>';
                    }
                    return $nama ?: 'Tidak ada nama';
                })
                ->addColumn('image', function ($row) {
                    $imageHtml = '';
                    foreach ($row->pengeluaran as $val) {
                        $imageUrl = $val->image ? asset('storage/' . $val->image) : asset('dash/images/cash.png');
                        $imageHtml .= '<a href="' . $imageUrl . '" target="_blank">
                                    <img src="' . $imageUrl . '" width="75" height="75" style="object-fit:cover; cursor:pointer;" />
                                </a><br>';
                    }
                    return $imageHtml ?: '<a href="' . asset('dash/images/cash.png') . '" target="_blank">
                                        <img src="' . asset('dash/images/cash.png') . '" width="100" height="100" style="object-fit:cover; cursor:pointer;" />
                                    </a>';
                })
                ->addColumn('description', function ($row) {
                    $deskripsi = '';
                    foreach ($row->pengeluaran as $val) {
                        $deskripsi .= $val->description . '<br>';
                    }
                    return $deskripsi ?: 'Tidak ada deskripsi';
                })
                ->addColumn('jumlah_satuan', function ($row) {
                    $jumlahSatuan = '';
                    foreach ($row->pengeluaran as $val) {
                        $jumlahSatuan .= $val->jumlah_satuan . '<br>';
                    }
                    return $jumlahSatuan ?: 'Tidak ada jumlah satuan';
                })
                ->addColumn('nominal', function ($row) {
                    $nominal = '';
                    foreach ($row->pengeluaran as $val) {
                        $nominal .= 'Rp' . number_format($val->nominal, 0, ',', '.') . '<br>';
                    }
                    return $nominal ?: 'Tidak ada nominal';
                })
                ->addColumn('dll', function ($row) {
                    $dll = '';
                    foreach ($row->pengeluaran as $val) {
                        $dll .= $val->dll . '<br>';
                    }
                    return $dll ?: 'Tidak ada data tambahan';
                })
                ->addColumn('jumlah', function ($row) {
                    $jumlah = $row->pengeluaran->sum('jumlah');
                    return 'Rp' . number_format($jumlah, 0, ',', '.') ?: 'Tidak ada jumlah';
                })
                ->addColumn('category', function ($row) {
                    $categories = '';
                    foreach ($row->pengeluaran as $val) {
                        $categories .= $val->category ? $val->category->name . '<br>' : 'Tidak ada kategori<br>';
                    }
                    return $categories;
                })
                ->addColumn('opsi', function ($row) {
                    $buttons = '
                    <div class="d-flex align-items-center">';

                    if (session('activeRole') == 'Admin' || session('activeRole') == 'Bendahara') {
                        $buttons .= '
                        <a href="' . route('pengeluaran.deleteAll', $row->id) . '" class="btn btn-danger btn-xs delete-all" data-id="' . $row->id . '">
                            <i class="fas fa-dumpster"></i>
                        </a>';
                    }

                    $buttons .= '</div>';

                    return $buttons;
                })


                ->rawColumns(['image', 'name', 'description', 'jumlah_satuan', 'nominal', 'dll', 'jumlah', 'category', 'opsi'])
                ->make(true);
        }
    }



    public function roles(Request $request)
    {
        if ($request->ajax()) {
            $role = Role::select(['id', 'name', 'guard_name'])->get();

            return DataTables::of($role)
                ->addIndexColumn()
                ->addColumn('opsi', function ($row) {
                    return '
                    <div class="d-flex align-items-center">
                        <form action="/role/' . $row->id . '/edit" method="GET" class="mr-1">
                            <button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></button>
                        </form>

                    </div>
                ';
                })
                ->rawColumns(['opsi'])
                ->make(true);
        }
    }

    public function reportIncome(Request $request)
    {

        if ($request->ajax()) {
            $startDate = $request->input('start_created_at');
            $endDate = $request->input('end_created_at');
            $pemasukan = Pemasukan::with('category')->select(['id_data', 'name', 'description', 'date', 'jumlah', 'id', 'created_at']);
            if ($startDate != null && $endDate != null) {
                $pemasukan = $pemasukan->whereBetween('created_at', [$startDate, $endDate]);
            }
            return DataTables::of($pemasukan)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category ? $row->category->name : 'Tidak ada kategori';
                })


                ->make(true);
        }
    }
}
