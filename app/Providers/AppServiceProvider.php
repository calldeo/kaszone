<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\View;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFour();
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
       
        $saldo = $totalPemasukan - $totalPengeluaran;
        View::share('saldo', $saldo);
    }
}