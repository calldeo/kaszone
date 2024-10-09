<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PemasukanController;
use App\Http\Controllers\API\PengeluaranController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
// use Auth; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/unautorized', function () {
    return response()->json([
        "status" => 401,
        "message" => "Unautorized"
    ]);
})->name('unautorized');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/validate', function (Request $request) {
        return response()->json([
            "status" => 200,
            "message" => "Authorized"
        ]);
    });
    Route::get('/logout', [AuthController::class, 'logout']);



      // User Route
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'dashboard']);
        Route::get('/all', [UserController::class, 'getAllUsers']);
        Route::get('/create', [UserController::class, 'create']);
        Route::post('/store', [UserController::class, 'store']);
        Route::get('/show/{id}', [UserController::class, 'show']);
        Route::put('/update/{id}', [UserController::class, 'update']);
        Route::delete('/destroy/{id}', [UserController::class, 'destroy']);
        Route::get('/profile', [UserController::class, 'profile']);
        Route::get('/profile/update', [UserController::class, 'updateProfile']);
        Route::get('/detail/{id}', [UserController::class, 'showDetail']);



    });


       // Category Route
    Route::prefix('category')->group(function () {
        Route::get('/all', [CategoryController::class, 'getAllCategory']);
        Route::get('/create', [CategoryController::class, 'create']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/show/{id}', [CategoryController::class, 'show']);
        Route::put('/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/destroy/{id}', [CategoryController::class, 'destroy']);
        Route::get('/detail/{id}', [CategoryController::class, 'showDetail']);
        Route::post('/category/import', [CategoryController::class, 'importExcel']);


    });


     Route::prefix('income')->group(function () {
        Route::get('/all', [PemasukanController::class, 'getAllIncome']);
        Route::get('/create', [PemasukanController::class, 'create']);
        Route::get('/saldo', [PemasukanController::class, 'saldo']);
        Route::post('/store', [PemasukanController::class, 'store']);
        Route::get('/show/{id}', [PemasukanController::class, 'show']);
        Route::put('/update/{id}', [PemasukanController::class, 'update']);
        Route::delete('/destroy/{id}', [PemasukanController::class, 'destroy']);
        Route::get('/detail/{id}', [PemasukanController::class, 'showDetail']);
        Route::post('/pemasukan/import', [PemasukanController::class, 'importExcel']);
        Route::get('/pemasukan/template', [PemasukanController::class, 'downloadTemplate']);
        Route::get('/pemasukan/cetak', [PemasukanController::class, 'cetakPemasukan']);
        Route::get('/export-pengeluaran', [PengeluaranController::class, 'exportPengeluaran']);



    });


       Route::prefix('outcome')->group(function () {
        Route::get('/all', [PengeluaranController::class, 'getAllOutcome']);
        Route::get('/create', [PengeluaranController::class, 'create']);
        Route::post('/store', [PengeluaranController::class, 'store']);
        Route::get('/show/{id}', [PengeluaranController::class, 'show']);
        Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update']); // Update pengeluaran
        Route::delete('/destroy/{id}', [PengeluaranController::class, 'destroy']);
        Route::get('/detail/{id}', [PengeluaranController::class, 'showDetail']);
        Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'delete']); // Delete individual pengeluaran
        Route::delete('/pengeluaran/parent/{id}', [PengeluaranController::class, 'deleteAll']);




    });



});























































// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();  

// });
// route::get('siswa',[SiswaController::class,'SiswaAPI']);
// route::post('siswa/update/{id_siswa}',[SiswaController::class,'updateAPI']);

// route::get('pelanggaran/show',[PelanggaranController::class,'show']);
// route::get('penghargaan/show',[PenghargaanController::class,'show']);
// route::get('penanganan/show', [PenangananController::class, 'show']);



// route::post('siswa',function(Request $request){
//     $valid = Auth::attempt($request->all());

//     if($valid){
//         $siswa = Auth::Siswa();
//         $siswa->api_token = Str::random(100);
//         $siswa->save();

//         // $user->makeVisible('api_token');

//         return $siswa;
//     }
//     return response()->json([
//         'message'=> 'email & password doesn\'t match'
//     ],404);

// });
// route::get('siswa',[SiswaController::class,'SiswaAPI']);
// route::post('login',[SiswaController::class,'loginapi']);

// route::post('siswa',function(Request $request){
//     $valid = Auth::attempt($request->all());

//     if($valid){
//         $siswa = Auth::Siswa();
//         $siswa->api_token = Str::random(100);
//         $siswa->save();

//         // $user->makeVisible('api_token');

//         return $siswa;
//     }
//     return response()->json([
//         'message'=> 'email & password doesn\'t match'
//     ],404);
// });
