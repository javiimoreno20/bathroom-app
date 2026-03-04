<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProfesorAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('profesor_id')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}