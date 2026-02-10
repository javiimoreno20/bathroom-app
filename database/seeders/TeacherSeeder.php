<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('teachers')->insert([
            'name' => 'javier',
            'email' => 'javiermm.04@gmail.com',
            'password' => '1234',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
