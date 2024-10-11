<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SettingSaldo;

class MinimalSaldoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil nilai saldo minimal dari model SettingSaldo
        $minimalSaldo = SettingSaldo::first()->saldo ?? 0;

        // Bagikan variabel ini ke semua view atau controller
        view()->share('minimalSaldo', $minimalSaldo);

        return $next($request);
    }
}
