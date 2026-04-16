<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserta el valor por defecto para el límite de permisos
        DB::table('settings')->updateOrInsert(
            ['key' => 'max_permissions'],
            ['value' => '5']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'max_daily_per_alumn'],
            ['value' => '3']
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'permission_duration_minutes'],
            ['value' => '15']
        );
    }
}