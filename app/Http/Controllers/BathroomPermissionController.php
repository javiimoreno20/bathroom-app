<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BathroomPermission;
use Illuminate\Support\Facades\Auth;


class BathroomPermissionController extends Controller
{
    //
    public function index() {
        BathroomPermission::whereNull('returned_at')
            ->where('created_at', '<=', now()->subMinutes(10))->update(['returned_at' => now()]);

        $activePermissions = BathroomPermission::whereNull('returned_at')->with('teacher')->get();

        $currentCount = $activePermissions->count();

        return view('dashboard', compact('currentCount', 'activePermissions'));
    }

    public function givePermission() {
        $currentCount = BathroomPermission::whereNull('returned_at')->count();

        if ($currentCount >= 5) {
            return back()->with('error', 'El baño está lleno');
        }

        BathroomPermission::create([
            'teacher_id' => Auth::id(),
        ]);

        return back()->with('success', 'Permiso concedido');
    }

    public function markReturned($id) {
        $permission = BathroomPermission::findOrFail($id);

        if ($permission->teacher_id !== Auth::id()) {
            abort(403);
        }

        $permission->update([
            'returned_at' => now()
        ]);

        return back();
    }
}
