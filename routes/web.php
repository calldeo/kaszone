<?php

use App\Models\Pengeluaran;
use App\Exports\PengeluaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
// use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
route::get('/',[LoginController::class,'landing'])->name('landing');

route::get('/login',[LoginController::class,'halamanlogin'])->name('login');
route::post('/postlogin',[LoginController::class,'postlogin'])->name('postlogin');
route::get('/logout',[LoginController::class,'logout'])->name('logout');
// Menampilkan form registrasi // Memproses registrasi
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

Route::post('/postregister', [RegisterController::class, 'register']);

Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');




Route::group(['middleware' => ['auth','ceklevel:admin,bendahara']], function (){
    route::get('/home',[HomeController::class,'index'])->name('home');
    
});






route::get('/table',[AdminController::class,'table'])->name('table');
route::get('/tab',[AdminController::class,'tab'])->name('tab');
route::get('/teb',[AdminController::class,'teb'])->name('teb');
route::get('/tob',[AdminController::class,'tob'])->name('tob');
route::get('/tabe',[AdminController::class,'tabe'])->name('tabe');






route::get('/admin/search',[AdminController::class,'search'])->name('admin.search');
route::get('/admin',[AdminController::class,'admin'])->name('admin');
// Route::delete('/admin/{id}', [AdminController::class,'destroy'])->name('admin.destroy');
Route::delete('/admin/{id}/destroy', [AdminController::class, 'destroy'])->name('admin.destroy');
route::get('/add_admin',[AdminController::class,'add_admin'])->name('add_admin');
Route::post('/admin/store',[AdminController::class,'store']);
Route::get('/admin/{id}/edit_admin  ',[AdminController::class,'edit']);
Route::put('/admin/{id}',[AdminController::class,'update']);



Route::post('/importbendahara', [BendaharaController::class, 'bendaharaimportexcel'])->name('import-bendahara');
route::get('/guru/search',[BendaharaController::class,'search'])->name('guru.search');
route::get('/bendahara',[BendaharaController::class,'bendahara'])->name('bendahara');
// Route::delete('/bendahara/{id}', [BendaharaController::class,'destroy'])->name('bendahara.destroy');
Route::delete('/bendahara/{id}/destroy', [BendaharaController::class, 'destroy'])->name('bendahara.destroy');
route::get('/add_bendahara',[BendaharaController::class,'add_bendahara'])->name('add_bendahara');
Route::post('/bendahara/store',[BendaharaController::class,'store']);
Route::get('/bendahara/{id}/edit_bendahara  ',[BendaharaController::class,'edit']);
Route::put('/guruu/{id}',[BendaharaController::class,'update']);







Route::get('/kategori', [CategoryController::class, 'index']);
route::get('/add_kategori',[CategoryController::class,'add_kategori'])->name('add_kategori');
Route::post('/kategori/store',[CategoryController::class,'store']);
Route::delete('/kategori/{id}/destroy', [CategoryController::class,'destroy'])->name('kategori.destroy');
Route::get('/kategori/{id}/edit_kategori  ',[CategoryController::class,'edit']);
Route::put('/kategori/{id}',[CategoryController::class,'update']);
Route::post('/importkategori', [CategoryController::class, 'kategoriimportexcel'])->name('import-kategori');
Route::get('/cetaklaporan',[CategoryController::class,'cetaklaporan'])->name('cetaklaporan');


route::get('/pemasukan',[PemasukanController::class,'index'])->name('index');
Route::get('/add_pemasukan', [PemasukanController::class, 'create']);
Route::post('/pemasukan/store', [PemasukanController::class, 'store']);
Route::delete('/pemasukan/{id_data}/destroy', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
Route::get('/pemasukan/{id_pemasukan}/edit_pemasukan',[PemasukanController::class,'edit']);
Route::put('/pemasukan/{id_pemasukan}', [PemasukanController::class, 'update'])->name('update');



route::get('/pengeluaran',[PengeluaranController::class,'index'])->name('index');
Route::get('/add_pengeluaran', [PengeluaranController::class, 'create']);
Route::post('/pengeluaran/store', [PengeluaranController::class, 'store']);
Route::delete('/pengeluaran/{id}/destroy', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
Route::get('/pengeluaran/{id_pengeluaran}/edit_pengeluaran',[PengeluaranController::class,'edit']);
Route::put('/pengeluaran/{id_pengeluaran}', [PengeluaranController::class, 'update'])->name('update');

// Route::get('kategori/teb', [CategoryController::class, 'teb'])->name('kategori.teb');
Route::get('/kategori/{id}/detail', [CategoryController::class, 'showDetail'])->name('kategori.showDetail');
Route::get('/cetaklaporan',[CategoryController::class,'cetaklaporan'])->name('cetaklaporan');
Route::get('/cetakpgl',[PengeluaranController::class,'cetakpgl'])->name('cetakpgl');
Route::get('pemasukan/tob', [PemasukanController::class, 'tob'])->name('pemasukan.tob');
Route::get('/pemasukan/{id_data}/detail', [PemasukanController::class, 'showDetail'])->name('pemasukan.showDetail');


Route::get('/export-pengeluaran', function () {
    return Excel::download(new PengeluaranExport, 'pengeluaran.xlsx');
});

Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::get('/pengeluaran/data', [AdminController::class, 'tabe'])->name('admin.tabe');
Route::get('pengeluaran/tabe', [PengeluaranController::class, 'tabe'])->name('pengeluaran.tabe');
Route::get('/pengeluaran/{id_data}/detail', [PengeluaranController::class, 'showDetail'])->name('pengeluaran.showDetail');
Route::get('/download-template-kategori', [CategoryController::class, 'downloadTemplate'])->name('download-template-kategori');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
