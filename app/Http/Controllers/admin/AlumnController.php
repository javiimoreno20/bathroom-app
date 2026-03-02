<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumn;
use App\Models\Course;
use Illuminate\View\View;

class AlumnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courseId = $request->course_id;

        $query = Alumn::with('course');

        if ($courseId) {
            $query->where('course_id', $courseId);
        }

        $alumns = $query->paginate(20); // Paginación, 20 por página

        $courses = Course::all();

        return view('Alumns.index', compact('alumns', 'courses', 'courseId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('Alumns.create', [
            'courses' => Course::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'full_name' => 'required|string|max:255',
            'course_id' => 'required|string|max:255',
        ]);
        Alumn::create($request->all());
        return redirect()->route('alumns.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        return view('Alumns.edit', [
            'alumn' => Alumn::findOrFail($id),
            'courses' => Course::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'full_name' => 'required|string|max:255',
            'course_id' => 'required|string|max:255',
        ]);
        $alumn = Alumn::findOrFail($id);
        $alumn->update($request->all());
        return redirect()->route('alumns.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $alumn = Alumn::findOrFail($id);
        $alumn->delete();
        return redirect()->route('alumns.index');
    }
}
