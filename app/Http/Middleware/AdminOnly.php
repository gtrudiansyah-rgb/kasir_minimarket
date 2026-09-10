<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (strtolower(auth()->user()->role ?? '') === 'kasir') {
            return redirect('/kasir')->with('error', 'Akses ditolak! Kasir tidak diizinkan mengelola data master.');
        }

        return $next($request);
    }
}