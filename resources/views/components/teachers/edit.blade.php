<div>
    @props(['teacher'])

    <h1>Editar Profesor</h1>

    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre completo:</label>
        <input type="text" name="full_name" 
               value="{{ old('full_name', $teacher->full_name) }}">
        <br><br>

        <label>Email:</label>
        <input type="email" name="email" 
               value="{{ old('email', $teacher->email) }}">
        <br><br>

        <label>Administrador:</label>

        <!-- Siempre envía un valor 0 si está desmarcado -->
        <input type="hidden" name="is_admin" value="0">

        <!-- Checkbox real, envía 1 si está marcado -->
        <input type="checkbox" name="is_admin" value="1"
            {{ old('is_admin', $teacher->is_admin) ? 'checked' : '' }}>

        <br><br>

        <button type="submit">Actualizar</button>
    </form>
    <a href="{{ route('teachers.index') }}">
        <button type="button">Volver</button>
    </a>
</div>