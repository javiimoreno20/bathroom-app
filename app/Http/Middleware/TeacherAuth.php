<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeacherAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('profesor')) {

            if (
                session()->has('pending_admin_id') &&
                !$request->routeIs('login.password')
            ) {
                return redirect()->route('login.password');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}