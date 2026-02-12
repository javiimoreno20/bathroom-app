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
        DB::table('teachers')->insert([
            'name' => 'javier',
            'email' => 'javiermm.04@gmail.com',
            'password' => Hash::make('1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
