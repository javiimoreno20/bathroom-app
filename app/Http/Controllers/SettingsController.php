<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'max_permissions' => 'required|integer|min:1',
            'max_daily_per_alumn' => 'required|integer|min:1',
            'permission_duration_minutes' => 'required|integer|min:1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'max_permissions'],
            ['value' => $request->max_permissions]
        );

        Setting::updateOrCreate(
            ['key' => 'max_daily_per_alumn'],
            ['value' => $request->max_daily_per_alumn]
        );

        Setting::updateOrCreate(
            ['key' => 'permission_duration_minutes'],
            ['value' => $request->permission_duration_minutes]
        );

        return back()->with('success', 'Configuración actualizada correctamente');
    }
}