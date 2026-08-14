<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Professor</title>
</head>
<body>
    <h1>Dashboard Professor</h1>
    <p>Bem-vindo, {{ Auth::user()->nome ?? 'Usuário' }}.</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Sair</button>
    </form>
</body>
</html>
