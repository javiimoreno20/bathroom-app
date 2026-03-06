<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('data/teachers.csv');

        if (!file_exists($path)) {
            throw new \Exception("El archivo CSV no existe en: {$path}");
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $rowData = array_combine($header, $row);

            Teacher::create([
                'full_name' => trim($rowData['full_name']),
                'email' => trim($rowData['email']),
                'is_admin' => !empty($rowData['is_admin'] ?? false),
            ]);
        }

        fclose($file);
    }
}