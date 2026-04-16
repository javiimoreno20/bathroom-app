<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BathroomPermission;
use App\Services\GoogleSheetsService;
use App\Models\Setting;

class ExportAndCleanPermissions extends Command
{
    protected $signature = 'permissions:export-clean';
    protected $description = 'Exporta permisos a Google Sheets y limpia la tabla';

    public function handle()
    {
        $minutes = (int) Setting::get('permission_duration_minutes', 15);

        $sheetService = new GoogleSheetsService();
        $spreadsheetId = '16IT-sjzeoA1-Is2gH94N0YJTPLvZfJmDRq4Vvs0yBcc';

        BathroomPermission::whereNull('returned_at')
            ->where('created_at', '<=', now()->subMinutes($minutes))
            ->get()
            ->each(function ($permission) use ($minutes) {
                $permission->update([
                    'returned_at' => $permission->created_at->copy()->addMinutes($minutes)
                ]);
            });

        $permissions = BathroomPermission::with('teacher', 'alumn')
            ->orderBy('created_at')
            ->get();

        $rows = [];

        foreach ($permissions as $permission) {
            $rows[] = [
                'alumn' => $permission->alumn?->full_name ?? 'Sin alumno',
                'teacher' => $permission->teacher?->full_name ?? 'Sin profesor',
                'created_at' => $permission->created_at,
                'returned_at' => $permission->returned_at
            ];
        }

        $sheetService->writeSheetData(
            $spreadsheetId,
            'bathroom_permissions!A:D',
            $rows
        );

        BathroomPermission::truncate();

        $this->info('Permisos exportados y base de datos limpiada correctamente.');
    }
}