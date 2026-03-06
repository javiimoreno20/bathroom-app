<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Teacher;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('Teachers.index', [
            'teachers' => Teacher::orderBy('id')->get(), // orden por id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('Teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:teachers,email',
        ]);

        Teacher::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'), // true o false
        ]);

        return redirect()->route('teachers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        //
        return view('Teachers.edit', [
            'teacher' => Teacher::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:teachers,email,' . $id,
        ]);

        $teacher->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin') // true si está marcado, false si no
        ]);

        // Si el profesor editado es el que está logueado, actualizar la sesión
        if (session('profesor') && session('profesor')->id == $teacher->id) {
            session()->put('profesor', $teacher);
        }

        return redirect()->route('teachers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return redirect()->route('teachers.index');
    }
}
