<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {

        //Devuelve la vista del formulario para iniciar sesión.
        return view('auth.login');
    }

    public function login(Request $request) {

        //Recoge del formulario los valores solicitados, los valida y si son válidos los guarda en una variable.
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        //Si consigue pasar el paso anterior comprueba que los valores obtenidos se encuentren en la base de datos para permitir el inicio de sesión. Si están en la base de datos crea una id de sesión única y redirige al index donde se pueden empezar a crear permisos y verlos.
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        //Si pasa del if significaría que las credenciales no eran correctas y el programa volvería a mandar a la vista del formulario de inicio de sesión con un mensaje de error.
        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ]);
    }

    public function logout(Request $request) {

        //Desloguea al profesor actual.
        Auth::logout();

        //Destruye la sesión actual.
        $request->session()->invalidate();

        //Genera un token nuevo CSRF para la sesión siguiente (SEGURIDAD).
        $request->session()->regenerateToken();

        //Redirige a la vista del formulario para iniciar sesión.
        return redirect('/login');
    }
}

