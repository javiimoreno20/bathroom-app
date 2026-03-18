<div>
    <h1>Control de Pasillo</h1>

    <h2>Actualmente en el pasillo: {{ $currentCount }}/{{ $maxPermissions }}</h2>

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

    <form method="POST" action="{{ route('give.permission') }}" onsubmit="return confirmPermission()">
        @csrf

        @if($alumns->isNotEmpty())
            <select name="alumn_id">
                @foreach($alumns as $alumn)
                    <option value="{{ $alumn->id }}">
                        {{ $alumn->full_name }}
                        ({{ $salidasHoy[$alumn->id] ?? 0 }} hoy)
                    </option>
                @endforeach
            </select>
            <br>

            <button type="submit">
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

    @if(session()->has('profesor') && session('profesor')->is_admin)
        <hr>
        <h2>Importaciones masivas</h2>
        <p>Importa los datos directamente desde Google Sheets.</p>

        <a href="https://docs.google.com/spreadsheets/d/16IT-sjzeoA1-Is2gH94N0YJTPLvZfJmDRq4Vvs0yBcc/edit?usp=sharing" target="_blank">
            <button type="button">Abrir Google Sheets</button>
        </a>

        <br><br>

        <form action="{{ route('import', ['type' => 'teachers']) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit">Importar Profesores</button>
        </form>

        <form action="{{ route('import', ['type' => 'alumns']) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit">Importar Alumnos</button>
        </form>

        <!-- NUEVOS BOTONES -->
        <h2>Edición Individual</h2>
        <a href="{{ route('teachers.index') }}">
            <button type="button">Ver Profesores</button>
        </a>

        <a href="{{ route('alumns.index') }}">
            <button type="button">Ver Alumnos</button>
        </a>

        <br><br>

        <h2>Historial de Permisos</h2>
        <a href="{{ route('permissions.history') }}">
            <button type="button">
                Ver historial de permisos
            </button>
        </a>

        <br><br>

        <h2>Configuración</h2>

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf

            <label>Máximo permisos activos:</label>
            <input type="number" name="max_permissions" value="{{ $maxPermissions }}">

            <label>Máximo diario por alumno:</label>
            <input type="number" name="max_daily_per_alumn" value="{{ $maxDailyPerAlumn }}">

            <button type="submit">Guardar</button>
        </form>

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

{{-- Transferimos datos PHP a JS sin errores de sintaxis --}}
<div id="dashboard-data"
     data-current-count="{{ $currentCount }}"
     data-max-permissions="{{ $maxPermissions }}"
     data-max-daily-per-alumn="{{ $maxDailyPerAlumn }}"
     data-salidas-hoy="{{ htmlspecialchars(json_encode($salidasHoy), ENT_QUOTES, 'UTF-8') }}">
</div>

<script>
    const dashboardDataEl = document.getElementById('dashboard-data');

    const currentCount = Number(dashboardDataEl.dataset.currentCount);
    const maxPermissions = Number(dashboardDataEl.dataset.maxPermissions);
    const maxDailyPerAlumn = Number(dashboardDataEl.dataset.maxDailyPerAlumn);
    const salidasHoy = JSON.parse(dashboardDataEl.dataset.salidasHoy);

    function confirmPermission() {
        const select = document.querySelector('select[name="alumn_id"]');
        const alumnId = select.value;
        const dailyCount = salidasHoy[alumnId] || 0;

        if (currentCount >= maxPermissions) {
            return confirm(`⚠️ Ya hay ${maxPermissions} permisos activos.\n¿Quieres dar otro permiso igualmente?`);
        }

        if (dailyCount >= maxDailyPerAlumn) {
            return confirm(`⚠️ Este alumno ya tiene ${dailyCount} permisos hoy (máximo recomendado ${maxDailyPerAlumn}).\n¿Quieres dar otro permiso igualmente?`);
        }

        return true;
    }
</script>
