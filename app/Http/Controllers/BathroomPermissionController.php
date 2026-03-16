<?php

namespace App\Http\Controllers;

use App\Models\Alumn;
use Illuminate\Http\Request;
use App\Models\BathroomPermission;
use App\Models\Course;
use App\Services\GoogleSheetsService;


class BathroomPermissionController extends Controller
{
    //
    public function index(Request $request) {

        //Filtro para permisos activos y comprueba si llevan más de 10 minutos, si lo lleva se actualiza el returned_at de null a la fecha actual.
        BathroomPermission::whereNull('returned_at')->where('created_at', '<=', now()->subMinutes(10))->update(['returned_at' => now()]);

        //Guarda en una variable todos los permisos que tengan null en returned_at y el profesor que haya creado el permiso.
        $activePermissions = BathroomPermission::whereNull('returned_at')->with('teacher', 'alumn')->get();

        //Cuenta todos los permisos que hay activos actualmente.
        $currentCount = $activePermissions->count();

        //Guardo en una variable todos los cursos.
        $courses = Course::all();

        $alumns = collect();

        $courseId = $request->course_id ?? null;
        
        if ($request->filled('course_id')) {
            $alumns = Alumn::where('course_id', $courseId)->get();
        }

        $salidasHoy = BathroomPermission::whereDate('created_at', now())->selectRaw('alumn_id, COUNT(*) as total')->groupBy('alumn_id')->pluck('total', 'alumn_id');

        // En tu BathroomPermissionController@index
        return response()
            ->view('dashboard', compact('currentCount', 'activePermissions', 'courses', 'alumns', 'courseId', 'salidasHoy'))
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');

        //Devuelve una vista enviándole la información de la cantidad de permisos activos actualmente y la información de cada permiso con la id del profesor que ha creado dicho permiso.
    }

    public function givePermission(Request $request) {

        $request->validate([
            'alumn_id' => 'required|exists:alumns,id'
        ]);

        //Guarda en una variable el número de permisos que hay activos.
        $currentCount = BathroomPermission::whereNull('returned_at')->count();

        //Comprueba si el contador de peermisos es mayor o igual a 5 para saltar un error.
        if ($currentCount >= 5) {
            return back()->with('error', 'El baño está lleno');
        }

        $profesor = session('profesor'); // trae el profesor de la sesión

        //Si pasa del if porque hay hueco para otro permiso, crea un permiso con la id del profesor logueado.
        BathroomPermission::create([
            'teacher_id' => $profesor->id,
            'alumn_id' => $request->alumn_id,
        ]);

        //Vuelve al index con la información de los permisos y un mensaje de que el permiso se ha creado correctamente.
        return back()->with('success', 'Permiso concedido');
    }

    public function markReturned($id) {
        $profesor = session('profesor'); // trae el profesor de la sesión
        
        //Guarda en una variable un permiso con la id solicitada.
        $permission = BathroomPermission::findOrFail($id);

        //Compara si el profesor logueado que esta intentando borrar un permiso es el mismo que lo ha creado, si no es el mismo salta un error de permisos y corta la ejecución.
        if ($permission->teacher_id !== $profesor->id) {
            abort(403);
        }

        //Si pasa del if se actualiza el returned_at del permiso de null a la fecha actual
        $permission->update([
            'returned_at' => now()
        ]);

        //Vuelve al index
        return back();
    }

    public function history()
    {
        $permissions = BathroomPermission::with('teacher', 'alumn')->orderBy('created_at', 'desc')->get();

        return view('bathroom_permissions.history', compact('permissions'));
    }

    public function exportPermissions()
    {
        $sheetService = new GoogleSheetsService();

        $spreadsheetId = '16IT-sjzeoA1-Is2gH94N0YJTPLvZfJmDRq4Vvs0yBcc';

        $permissions = BathroomPermission::with('teacher','alumn')->get();

        $rows = [];

        foreach ($permissions as $permission) {
            $rows[] = [
                'alumn' => $permission->alumn?->full_name ?? 'Sin alumno',
                'teacher' => $permission->teacher?->full_name ?? 'Sin profesor',
                'created_at' => $permission->created_at,
                'returned_at' => $permission->returned_at
            ];
        }

        try {
            $sheetService->writeSheetData(
                $spreadsheetId,
                'bathroom_permissions!A:D',
                $rows
            );
        } catch (\Exception $e) {
            logger('Error exportando permisos', ['message' => $e->getMessage()]);
            return back()->with('error','Error exportando datos: '.$e->getMessage());
        }

        return back()->with('success','Permisos exportados correctamente a Google Sheets.');
    }
}
