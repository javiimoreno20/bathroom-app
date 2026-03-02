<div>
    <h1>Editar Alumno</h1>

    <form action="{{ route('alumns.update', $alumn->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre completo:</label>
        <input type="text" name="full_name" 
               value="{{ old('full_name', $alumn->full_name) }}">
        <br><br>

        <label>Curso:</label>
        <select name="course_id">
            @foreach($courses as $course)
                <option value="{{ $course->id }}"
                    {{ $alumn->course_id == $course->id ? 'selected' : '' }}>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
        <br><br>

        <button type="submit">Actualizar</button>
    </form>
</div>