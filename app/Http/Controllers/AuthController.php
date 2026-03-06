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
        // Validación del email
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Buscar al profesor usando whereEncrypted
        $profesor = Teacher::whereEncrypted('email', $validated['email'])->first();

        if ($profesor) {
            // Regenerar ID de sesión por seguridad
            $request->session()->regenerate();

            // Guardar el profesor completo en sesión
            $request->session()->put('profesor', $profesor);

            // Redirigir al dashboard
            return redirect()->route('dashboard');
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
        session()->forget('profesor');

        // Invalidar sesión actual
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        // Redirigir al login
        return redirect()->route('login');
    }
}