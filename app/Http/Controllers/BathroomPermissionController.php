<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BathroomPermission;
use Illuminate\Support\Facades\Auth;


class BathroomPermissionController extends Controller
{
    //
    public function index() {

        //Filtro para permisos activos y comprueba si llevan más de 10 minutos, si lo lleva se actualiza el returned_at de null a la fecha actual.
        BathroomPermission::whereNull('returned_at')->where('created_at', '<=', now()->subMinutes(10))->update(['returned_at' => now()]);

        //Guarda en una variable todos los permisos que tengan null en returned_at y el profesor que haya creado el permiso.
        $activePermissions = BathroomPermission::whereNull('returned_at')->with('teacher')->get();

        //Cuenta todos los permisos que hay activos actualmente.
        $currentCount = $activePermissions->count();

        //Devuelve una vista enviándole la información de la cantidad de permisos activos actualmente y la información de cada permiso con la id del profesor que ha creado dicho permiso.
        return view('dashboard', compact('currentCount', 'activePermissions'));
    }

    public function givePermission() {

        //Guarda en una variable el número de permisos que hay activos.
        $currentCount = BathroomPermission::whereNull('returned_at')->count();

        //Comprueba si el contador de peermisos es mayor o igual a 5 para saltar un error.
        if ($currentCount >= 5) {
            return back()->with('error', 'El baño está lleno');
        }

        //Si pasa del if porque hay hueco para otro permiso, crea un permiso con la id del profesor logueado.
        BathroomPermission::create([
            'teacher_id' => Auth::id(),
        ]);

        //Vuelve al index con la información de los permisos y un mensaje de que el permiso se ha creado correctamente.
        return back()->with('success', 'Permiso concedido');
    }

    public function markReturned($id) {
        //Guarda en una variable un permiso con la id solicitada.
        $permission = BathroomPermission::findOrFail($id);

        //Compara si el profesor logueado que esta intentando borrar un permiso es el mismo que lo ha creado, si no es el mismo salta un error de permisos y corta la ejecución.
        if ($permission->teacher_id !== Auth::id()) {
            abort(403);
        }

        //Si pasa del if se actualiza el returned_at del permiso de null a la fecha actual
        $permission->update([
            'returned_at' => now()
        ]);

        //Vuelve al index
        return back();
    }
}
