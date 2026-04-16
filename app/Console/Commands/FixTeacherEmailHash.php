<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Teacher;

class FixTeacherEmailHash extends Command
{
    protected $signature = 'teachers:fix-email-hash';
    protected $description = 'Regenera email_hash para todos los profesores';

    public function handle()
    {
        $count = 0;

        Teacher::chunk(100, function ($teachers) use (&$count) {
            foreach ($teachers as $teacher) {

                try {
                    // 👇 Esto usa el getter → email ya desencriptado
                    $email = $teacher->email;

                    if ($email) {
                        $teacher->email_hash = hash('sha256', strtolower($email));
                        $teacher->save();
                        $count++;
                    }

                } catch (\Exception $e) {
                    $this->error("Error en teacher ID {$teacher->id}");
                }
            }
        });

        $this->info("✔ Hashes regenerados: {$count}");
    }
}