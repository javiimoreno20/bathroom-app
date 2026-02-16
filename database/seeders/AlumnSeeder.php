<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/students.csv');

        if (!file_exists($path)) {
            throw new \Exception("El archivo CSV no existe en: {$path}");
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        $allRows = [];
        $uniqueCourseNames = [];

        //Leemos todo el archivo primero para recolectar nombres de cursos.
        while (($row = fgetcsv($file)) !== false) {
            $rowData = array_combine($header, $row);
            $courseName = trim($rowData['curso']);
            
            $allRows[] = $rowData;
            if (!in_array($courseName, $uniqueCourseNames)) {
                $uniqueCourseNames[] = $courseName;
            }
        }
        fclose($file);

        //ORDENAR LOS CURSOS (Lógica personalizada).
        usort($uniqueCourseNames, function($a, $b) {

            //Definimos prioridades de palabras clave.
            $priorities = ['ESO' => 1, 'BACH' => 2, 'IF' => 3];
            
            $getPriority = function($name) use ($priorities) {
                foreach ($priorities as $key => $p) {
                    if (str_contains(strtoupper($name), $key)) return $p;
                }
                return 99;
            };

            $pA = $getPriority($a);
            $pB = $getPriority($b);

            if ($pA != $pB) return $pA <=> $pB; //Ordenar por ESO, luego BACH...
            return $a <=> $b; //Si son del mismo tipo (ej. 1º ESO vs 2º ESO), orden alfabético.
        });

        //Insertar cursos ordenados y guardar sus IDs.
        $courseMap = [];
        foreach ($uniqueCourseNames as $name) {
            $id = DB::table('courses')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $courseMap[$name] = $id;
        }

        //Insertar alumnos usando el mapa de IDs.
        $studentsData = [];
        $batchSize = 500;

        foreach ($allRows as $rowData) {
            $studentsData[] = [
                'full_name' => trim($rowData['full_name']),
                'course_id' => $courseMap[trim($rowData['curso'])],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($studentsData) === $batchSize) {
                DB::table('alumns')->insert($studentsData);
                $studentsData = [];
            }
        }

        if (!empty($studentsData)) {
            DB::table('alumns')->insert($studentsData);
        }
    }
}
