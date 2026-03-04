<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Valida que el email tenga formato correcto
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Busca al profesor en la base de datos
        $profesor = DB::table('teachers')->where('email', $validated['email'])->first();

        if ($profesor) {
            dd($profesor, $profesor->id ?? $profesor->profesor_id);
            // Crear sesión manualmente
            session(['teacher_id' => $profesor->id]);

            // Regenerar ID de sesión por seguridad
            $request->session()->regenerate();

            // Redirigir al dashboard
            return redirect('/dashboard');
        }

        // Si no existe el email, devolver error
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
        session()->forget('profesor_id');

        // Invalidar sesión actual
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        // Redirigir al login
        return redirect('/login');
    }
}