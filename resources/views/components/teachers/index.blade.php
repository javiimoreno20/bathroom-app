<div>
    @props(['teachers'])

    <h1>Lista de Profesores</h1>

    <a href="{{ route('dashboard') }}">
        <button type="button">Volver a la página principal</button>
    </a>

    <br><br>

    <a href="{{ route('teachers.create') }}">
        <button type="button">Crear nuevo profesor</button>
    </a>

    <br><br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->id }}</td>
                    <td>{{ $teacher->full_name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>
                        <a href="{{ route('teachers.edit', $teacher->id) }}">
                            <button type="button">Editar</button>
                        </a>

                        <form action="{{ route('teachers.destroy', $teacher->id) }}" 
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
</div>