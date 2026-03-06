<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeacherAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('profesor')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
