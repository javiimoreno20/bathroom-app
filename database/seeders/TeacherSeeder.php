<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // DB::table('teachers')->insert([
        //     'full_name' => 'javier',
        //     'email' => 'javiermm.04@gmail.com',
        //     'password' => Hash::make('1234'),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        //Ruta del CSV.
        $path = database_path('data/teachers.csv');

        //Si no encuentra el archivo lanza una excepción.
        if (!file_exists($path)) {
            throw new \Exception("El archivo CSV no existe en: {$path}");
        }

        //Abre el archivo csv.
        $file = fopen($path, 'r');

        //Leemos la cabecera.
        $header = fgetcsv($file);

        //Arrays para almacenar datos en batch.
        $teachersData = [];

        //Iniciamos un bucle del que no saldrá hasta que no queden líneas en el csv.
        while (($row = fgetcsv($file)) !== false) {

            $rowData = array_combine($header, $row);

            //Divide las columnas del csv entre nombre de alumno y curso.
            $full_name = trim($rowData['full_name']);
            $email = trim($rowData['email']);
            $password = trim($rowData['password']);

            //Añadimos el alumno al array de batch.
            $teachersData[] = [
                'full_name' => $full_name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => isset($row['is_admin']) ? (bool)$row['is_admin'] : false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        //Insertar los datos.
        if (!empty($teachersData)) {
            DB::table('teachers')->insert($teachersData);
        }

        //Cerramos el archivo csv.
        fclose($file);
    }
}
