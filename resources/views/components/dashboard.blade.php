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
                <option value="{{ $course->id }}">{{ $course->name }}</option>
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
        @else
            <p>Selecciona un Curso</p>
        @endif

        <button type="submit" {{ $currentCount >= 5 ? 'disabled' : '' }}>
            Dar permiso
        </button>
    </form>

    <form method="POST" action="{{ route('mark.returned') }}">
        @csrf
        <button type="submit">
            Cerrar Sesión
        </button>
    </form>

    <hr>

    <h3>Permisos activos:</h3>

    @foreach($activePermissions as $permission)
        <div style="margin-bottom: 10px;">
            Profesor: {{ $permission->teacher->full_name }}
            Alumno: {{ $permission->alumn->full_name }}
            | Salió hace: {{ $permission->created_at->diffForHumans() }}

            <form method="POST" action="{{ route('mark.returned', $permission->id) }}" style="display:inline;">
                @csrf
                <button type="submit">Ha regresado</button>
            </form>
        </div>
    @endforeach

</div>
