<div>
    <h1>Contraseña de Administrador</h1>

    @if($errors->any())
        <div style="color:red">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.password.check') }}">
        @csrf

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit">Entrar</button>
    </form>

    <br>

    <form method="GET" action="{{ route('login') }}">
        <button type="submit">Volver</button>
    </form>
</div>