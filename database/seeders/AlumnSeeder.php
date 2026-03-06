<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumn;
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

        // Leemos todo el CSV primero
        while (($row = fgetcsv($file)) !== false) {
            $rowData = array_combine($header, $row);
            $courseName = trim($rowData['curso']);
            
            $allRows[] = $rowData;
            if (!in_array($courseName, $uniqueCourseNames)) {
                $uniqueCourseNames[] = $courseName;
            }
        }
        fclose($file);

        // Ordenar cursos (ESO, BACH, IF)
        usort($uniqueCourseNames, function($a, $b) {
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

        // Insertar cursos y guardar IDs
        $courseMap = [];
        foreach ($uniqueCourseNames as $name) {
            $courseMap[$name] = DB::table('courses')->insertGetId([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insertar alumnos con Eloquent (para cifrado)
        foreach ($allRows as $rowData) {
            $courseName = trim($rowData['curso']);
            if (!isset($courseMap[$courseName])) continue;

            Alumn::create([
                'full_name' => trim($rowData['full_name']),
                'course_id' => $courseMap[$courseName],
            ]);
        }
    }
}