<div>
    <h1>Login Profesores</h1>

    @if($errors->any())
        <div style="color:red">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" placeholder="Introduce tu correo corporativo" required><br><br>

        <label>Contraseña:</label>
        <input type="password" name="password" placeholder="Email sin @g.educaand.es" required><br><br>

        <button type="submit">Entrar</button>
    </form>

</div>
