<div>
    <h2>Importar Profesores</h2>

    <form method="POST" action="{{ route('import', ['type' => 'teachers']) }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" required>
        <button type="submit">Importar CSV</button>
    </form>
</div>