<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumn;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class importController extends Controller
{
    //
    public function showTeachersForm() {
        return view('importTeachers');
    }

    public function showAlumnsForm() {
        return view('importAlumns');
    }

    public function import(Request $request, $type) {
        // Validación del archivo CSV
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'No se pudo abrir el archivo CSV.');
        }

        $header = fgetcsv($handle);
        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($header, $data);
        }
        fclose($handle);

        DB::transaction(function () use ($rows, $type) {

            if ($type === 'teachers') {
                // Sobrescribir todo
                DB::table('teachers')->truncate();

                foreach ($rows as $row) {
                    Teacher::updateOrCreate(
                        ['email' => trim($row['email'])],
                        [
                            'full_name' => trim($row['full_name']),
                            'password' => Hash::make(trim($row['password'])),
                            'is_admin' => isset($row['is_admin']) ? (bool)$row['is_admin'] : false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

            } elseif ($type === 'alumns') {
                // Sobrescribir todo
                DB::table('alumns')->truncate();
                DB::table('courses')->truncate();

                // Obtener cursos únicos
                $uniqueCourses = [];
                foreach ($rows as $row) {
                    $courseName = trim($row['curso']);
                    if (!in_array($courseName, $uniqueCourses)) {
                        $uniqueCourses[] = $courseName;
                    }
                }

                // Ordenar cursos según tu lógica personalizada
                usort($uniqueCourses, function($a, $b) {
                    $priorities = ['ESO' => 1, 'BACH' => 2, 'IF' => 3];
                    $getPriority = function($name) use ($priorities) {
                        foreach ($priorities as $key => $p) {
                            if (str_contains(strtoupper($name), $key)) return $p;
                        }
                        return 99;
                    };
                    $pA = $getPriority($a);
                    $pB = $getPriority($b);
                    if ($pA != $pB) return $pA <=> $pB;
                    return $a <=> $b;
                });

                // Insertar cursos y crear mapa de IDs
                $courseMap = [];
                foreach ($uniqueCourses as $name) {
                    $courseMap[$name] = DB::table('courses')->insertGetId([
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Insertar alumnos
                foreach ($rows as $row) {
                    Alumn::create([
                        'full_name' => trim($row['full_name']),
                        'course_id' => $courseMap[trim($row['curso'])],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

        }); // fin de la transacción

        return back()->with('success', 'Importación completada correctamente.');
    }
}
