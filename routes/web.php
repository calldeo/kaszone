<?php

use App\Models\Category;
use App\Models\Pengeluaran;
use App\Exports\PengeluaranExport;
use App\Exports\PemasukanExport;

use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
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


route::get('/',[LoginController::class,'landing'])->name('landing');

route::get('/login',[LoginController::class,'halamanlogin'])->name('login');
route::post('/postlogin',[LoginController::class,'postlogin'])->name('postlogin');


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Menampilkan form registrasi // Memproses registrasi
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

Route::post('/postregister', [RegisterController::class, 'register']);

Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');




Route::group(['middleware' => ['auth', 'permission:Home']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});





route::get('/table',[AdminController::class,'table'])->name('table');
route::get('/users',[AdminController::class,'users'])->name('users');
route::get('/kategoris',[AdminController::class,'kategoris'])->name('kategoris');
route::get('/income',[AdminController::class,'income'])->name('income');
route::get('/production',[AdminController::class,'production'])->name('production');
route::get('/roles',[AdminController::class,'roles'])->name('roles');







Route::group(['middleware' => ['auth','permission:Bendahara']], function (){

    Route::post('/importbendahara', [BendaharaController::class, 'bendaharaimportexcel'])->name('import-bendahara');
    route::get('/user',[BendaharaController::class,'bendahara'])->name('bendahara');
    Route::delete('/user/{id}/destroy', [BendaharaController::class, 'destroy'])->name('user.destroy');
    route::get('/add_user',[BendaharaController::class,'add_user'])->name('add_user');
    Route::post('/user/store',[BendaharaController::class,'store']);
    Route::get('/user/{id}/edit_user  ',[BendaharaController::class,'edit']);
    Route::put('/guruu/{id}',[BendaharaController::class,'update']);
    Route::get('/user/{id}/detail', [BendaharaController::class, 'showDetail'])->name('user.showDetail');

});



Route::group(['middleware' => ['auth','permission:Kategori']], function (){

    Route::get('/kategori', [CategoryController::class, 'index']);
    route::get('/add_kategori',[CategoryController::class,'add_kategori'])->name('add_kategori');
    Route::post('/kategori/store',[CategoryController::class,'store']);
    Route::delete('/kategori/{id}/destroy', [CategoryController::class,'destroy'])->name('kategori.destroy');
    Route::get('/kategori/{id}/edit_kategori  ',[CategoryController::class,'edit']);
    Route::put('/kategori/{id}',[CategoryController::class,'update']);
    Route::post('/importkategori', [CategoryController::class, 'kategoriimportexcel'])->name('import-kategori');
    Route::get('/cetaklaporan',[CategoryController::class,'cetaklaporan'])->name('cetaklaporan');
    Route::get('/kategori/{id}/detail', [CategoryController::class, 'showDetail'])->name('kategori.showDetail');

});

Route::group(['middleware' => ['auth','permission:Data Pemasukan']], function (){

    route::get('/pemasukan',[PemasukanController::class,'index'])->name('index');
    Route::get('/add_pemasukan', [PemasukanController::class, 'create']);
    Route::post('/pemasukan/store', [PemasukanController::class, 'store']);
    Route::delete('/pemasukan/{id}/destroy', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
    Route::get('/pemasukan/{id_pemasukan}/edit_pemasukan',[PemasukanController::class,'edit']);
    Route::put('/pemasukan/{id_pemasukan}', [PemasukanController::class, 'update'])->name('update');
    Route::get('pemasukan/tob', [PemasukanController::class, 'tob'])->name('pemasukan.tob');
    Route::get('/pemasukan/{id_data}/detail', [PemasukanController::class, 'showDetail'])->name('pemasukan.showDetail');
    Route::post('/importpemasukan', [PemasukanController::class, 'pemasukanImportExcel'])->name('import-pemasukan');
    Route::get('/download-template-pemasukan', [PemasukanController::class, 'downloadTemplate'])->name('download-template-pemasukan');
        Route::get('/cetak-pemasukan',[PemasukanController::class,'cetakPemasukan'])->name('cetak-pemasukan');

    
    Route::get('/export-pemasukan', function () {
        return Excel::download(new PemasukanExport, 'pemasukan.xlsx');});
// routes/web.php
Route::get('/get-categories/{jenis_kategori}', [PemasukanController::class, 'getCategories']);


});

Route::group(['middleware' => ['auth','permission:Data Pengeluaran']], function (){

    route::get('/pengeluaran',[PengeluaranController::class,'index'])->name('index');
    Route::get('/add_pengeluaran', [PengeluaranController::class, 'create']);
    Route::post('/pengeluaran/store', [PengeluaranController::class, 'store']);
    // Route::delete('/pengeluaran/{id}/destroy', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
    // Route::get('/pengeluaran/{id_pengeluaran}/edit_pengeluaran',[PengeluaranController::class,'edit']);
    // Route::put('/pengeluaran/{id_pengeluaran}', [PengeluaranController::class, 'update'])->name('update');
    Route::get('/pengeluaran/{id_data}/edit', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
    Route::put('/pengeluaran/{id_data}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::get('/cetakpgl',[PengeluaranController::class,'cetakpgl'])->name('cetakpgl');
    Route::get('/export-pengeluaran', function () {return Excel::download(new PengeluaranExport, 'pengeluaran.xlsx');});
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('/pengeluaran/data', [AdminController::class, 'tabe'])->name('admin.tabe');
    Route::get('pengeluaran/tabe', [PengeluaranController::class, 'tabe'])->name('pengeluaran.tabe');
    Route::get('/pengeluaran/{id_data}/detail', [PengeluaranController::class, 'showDetail'])->name('pengeluaran.showDetail');
    Route::get('/download-template-kategori', [CategoryController::class, 'downloadTemplate'])->name('download-template-kategori');
    Route::post('/pengeluaran/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('/pengeluaran/delete/{id_data}', [PengeluaranController::class, 'delete'])->name('pengeluaran.delete');
Route::get('/pengeluaran/deleteAll', [PengeluaranController::class, 'deleteAll'])->name('pengeluaran.deleteAll');

    
});
Route::group(['middleware' => ['auth','permission:Role']], function (){

    route::get('/role',[RoleController::class,'role'])->name('role');
    Route::get('/role/{id}/edit_role  ',[RoleController::class,'edit']);
    Route::put('/role/{id}',[RoleController::class,'update']);
    Route::get('/add_role', [RoleController::class, 'create']);
    Route::post('/role/store', [RoleController::class, 'store']);
});
Route::post('/switch-role', [BendaharaController::class, 'switchRole'])->name('switchRole');
