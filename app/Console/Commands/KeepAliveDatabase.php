<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KeepAliveDatabase extends Command
{
    protected $signature = 'db:keep-alive';
    protected $description = 'Mantiene activa la base de datos';

    public function handle()
    {
        try {
            // Consulta simple a tabla real
            DB::table('teachers')->count();

            $this->info('Keep alive ejecutado correctamente');
        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());
        }
    }
}