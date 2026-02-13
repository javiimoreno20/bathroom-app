<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnSeeder extends Seeder
{
    public function run(): void
    {
        //Ruta del CSV.
        $path = database_path('data/students.csv');

        //Si no encuentra el archivo lanza una excepción.
        if (!file_exists($path)) {
            throw new \Exception("El archivo CSV no existe en: {$path}");
        }

        //Abre el archivo csv.
        $file = fopen($path, 'r');

        //Leemos la cabecera.
        $header = fgetcsv($file);

        //Arrays para almacenar datos en batch.
        $studentsData = [];
        $existingCourses = []; //Para no duplicar cursos.

        //Tamaño establecido para guardar registros a la vez.
        $batchSize = 500;

        //Iniciamos un bucle del que no saldrá hasta que no queden líneas en el csv.
        while (($row = fgetcsv($file)) !== false) {


            $rowData = array_combine($header, $row);

            //Divide las columnas del csv entre nombre de alumno y curso.
            $courseName = trim($rowData['curso']);
            $studentName = trim($rowData['full_name']);

            //Creamos el curso si no existe.
            if (!isset($existingCourses[$courseName])) {
                $courseId = DB::table('courses')->insertGetId([
                    'name' => $courseName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $existingCourses[$courseName] = $courseId;
            }

            //Añadimos el alumno al array de batch.
            $studentsData[] = [
                'full_name' => $studentName,
                'course_id' => $existingCourses[$courseName],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            //Inserción por batch.
            if (count($studentsData) === $batchSize) {
                DB::table('alumns')->insert($studentsData);
                $studentsData = [];
            }
        }

        //Insertar los que queden.
        if (!empty($studentsData)) {
            DB::table('alumns')->insert($studentsData);
        }

        //Cerramos el archivo csv.
        fclose($file);
    }
}
