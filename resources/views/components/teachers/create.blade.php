<div>
    <h1>Crear Profesor</h1>

    <form action="{{ route('teachers.store') }}" method="POST">
        @csrf

        <label>Nombre completo:</label>
        <input type="text" name="full_name" value="{{ old('full_name') }}">
        <br><br>

        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}">
        <br><br>

        <label>
            <input type="checkbox" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
            Administrador
        </label>
        <br><br>

        <button type="submit">Guardar</button>
    </form>
    <a href="{{ route('teachers.index') }}">
        <button type="button">Volver</button>
    </a>
</div>