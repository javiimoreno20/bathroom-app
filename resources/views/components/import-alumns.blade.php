<div>
    <h2>Importar Alumnos</h2>

    <form method="POST" action="{{ route('import', ['type' => 'alumns']) }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file">
        <button type="submit">Importar Alumnos</button>
    </form>
</div>