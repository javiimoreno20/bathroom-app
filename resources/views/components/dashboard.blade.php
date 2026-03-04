<div>
    <h1>Control de Baño</h1>

    <h2>Actualmente en el baño: {{ $currentCount }}/5</h2>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form method="GET" action="{{ route('dashboard') }}">
        <select name="course_id">
            @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
        <button type="submit">
            Filtrar
        </button>
    </form>

    <form method="POST" action="{{ route('give.permission') }}">
        @csrf

        @if($alumns->isNotEmpty())
            <select name="alumn_id">
                @foreach($alumns as $alumn)
                    <option value="{{ $alumn->id }}">{{ $alumn->full_name }}</option>
                @endforeach
            </select>
            <br>

            <button type="submit" {{ $currentCount >= 5 ? 'disabled' : '' }}>
                Dar permiso
            </button>
        @else
            <p>Selecciona un Curso:</p>
        @endif
    </form>

    <hr>

    <h3>Permisos activos:</h3>

    @foreach($activePermissions as $permission)
        <div style="margin-bottom: 10px;">
            Profesor: {{ $permission->teacher->full_name }}
            | Alumno: {{ $permission->alumn?->full_name ?? 'Sin alumno' }}
            | Salió hace: {{ $permission->created_at->diffForHumans() }}

            <form method="POST" action="{{ route('mark.returned', $permission->id) }}" style="display:inline;">
                @csrf
                <button type="submit">Ha regresado</button>
            </form>
        </div>
    @endforeach

    <br>

    @if(session()->has('teacher') && session('teacher')->is_admin)
        <pre>{{ var_dump(session('profesor')) }}</pre>
        <hr>
        <h2>Importaciones masivas</h2>
        <p>Importa los datos directamente desde Google Sheets.</p>

        <form action="{{ route('import', ['type' => 'teachers']) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit">Importar Teachers</button>
        </form>

        <form action="{{ route('import', ['type' => 'alumns']) }}" method="POST" style="display:inline-block; margin-left:10px;">
            @csrf
            <button type="submit">Importar Alumnos</button>
        </form>

        <!-- NUEVOS BOTONES -->
         <h2>Edición Individual</h2>
        <a href="{{ route('teachers.index') }}">
            <button type="button">Ver Profesores</button>
        </a>

        <a href="{{ route('alumns.index') }}" style="margin-left:10px;">
            <button type="button">Ver Alumnos</button>
        </a>

        <br><br>
    @endif

    <br><br>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">
            Cerrar Sesión
        </button>
    </form>

</div>
