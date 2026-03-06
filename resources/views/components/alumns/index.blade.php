<div>
    @props(['alumns', 'courses', 'courseId'])

    <h1>Lista de Alumnos</h1>

    <a href="{{ route('dashboard') }}">
        <button type="button">Volver a la página principal</button>
    </a>

    <br><br>

    <a href="{{ route('alumns.create') }}">
        <button type="button">Crear nuevo alumno</button>
    </a>

    <br><br>

    <!-- FILTRO POR CURSO -->
    <form method="GET" action="{{ route('alumns.index') }}">
        <select name="course_id">
            <option value="">-- Todos los cursos --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}"
                    {{ $courseId == $course->id ? 'selected' : '' }}>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Curso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alumns as $alumn)
                <tr>
                    <td>{{ $alumn->id }}</td>
                    <td>{{ $alumn->full_name }}</td>
                    <td>{{ $alumn->course->name ?? 'Sin curso' }}</td>
                    <td>
                        <a href="{{ route('alumns.edit', $alumn->id) }}">
                            <button type="button">Editar</button>
                        </a>

                        <form action="{{ route('alumns.destroy', $alumn->id) }}" 
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
</div>