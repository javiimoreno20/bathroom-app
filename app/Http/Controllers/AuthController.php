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

        $emailHash = hash('sha256', strtolower($validated['email']));

        $profesor = Teacher::where('email_hash', $emailHash)->first();

        if (!$profesor) {
            return back()->withErrors([
                'email' => 'Profesor no registrado.',
            ]);
        }

        // 👇 SI NO ES ADMIN → LOGIN DIRECTO
        if (!$profesor->is_admin) {
            $request->session()->regenerate();
            $request->session()->put('profesor', $profesor);
            return redirect()->route('dashboard');
        }

        // 👇 SI ES ADMIN → GUARDAR TEMP Y IR A PASSWORD
        $request->session()->regenerate();
        $request->session()->put('pending_admin_id', $profesor->id);

        return redirect()->route('login.password');
    }

    public function showPassword()
    {
        if (!session()->has('pending_admin_id')) {
            return redirect()->route('login');
        }

        $teacher = Teacher::find(session('pending_admin_id'));

        if (!$teacher || !$teacher->is_admin) {
            return redirect()->route('login');
        }

        return view('auth.admin-password');
    }

    public function checkPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string']
        ]);

        $teacher = Teacher::find(session('pending_admin_id'));

        if (!$teacher) {
            return redirect()->route('login');
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $teacher->password)) {
            return back()->withErrors([
                'password' => 'Contraseña incorrecta'
            ]);
        }

        session()->forget('pending_admin_id');

        $request->session()->regenerate();

        $request->session()->put('profesor', $teacher);

        return redirect()->route('dashboard');
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