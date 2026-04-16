<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Teacher;

class SetDefaultPasswordsForAdmins extends Command
{
    protected $signature = 'teachers:set-default-passwords';
    protected $description = 'Asigna contraseña por defecto a admins sin password';

    public function handle()
    {
        $admins = Teacher::where('is_admin', true)
            ->whereNull('password')
            ->get();

        foreach ($admins as $admin) {
            $admin->password = 'admin123';
            $admin->save();
        }

        $this->info("Contraseñas asignadas a {$admins->count()} administradores.");
    }
}