<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Iniciar sesión sin contraseña (solo email)
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $profesor = Teacher::all()->first(fn($t) => $t->email === $validated['email']);

        if ($profesor) {
            $request->session()->regenerate();
            $request->session()->put('profesor', $profesor);

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Profesor no registrado.',
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        // Borrar sesión del profesor
        session()->forget('profesor');

        // Invalidar sesión actual
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        // Redirigir al login
        return redirect()->route('login');
    }
}