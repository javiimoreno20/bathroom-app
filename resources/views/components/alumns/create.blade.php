<div>
    <h1>Crear Alumno</h1>

    <form action="{{ route('alumns.store') }}" method="POST">
        @csrf

        <label>Nombre completo:</label>
        <input type="text" name="full_name" value="{{ old('full_name') }}">
        <br><br>

        <label>Curso:</label>
        <select name="course_id">
            @foreach($courses as $course)
                <option value="{{ $course->id }}">
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
        <br><br>

        <button type="submit">Guardar</button>
    </form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>