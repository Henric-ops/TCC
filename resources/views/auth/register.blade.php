<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LumiKids - Criar conta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
    <div class="register-page">
        <aside class="register-side">
            <div class="register-brand">
                <div class="brand-logo" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M4 18 L24 8 L44 18 L24 28 Z" />
                        <path d="M13 22.5 V33 C13 33 17 38 24 38 C31 38 35 33 35 33 V22.5" />
                        <path d="M44 18 V29" />
                        <circle cx="44" cy="32.5" r="2" fill="#fff" stroke="none" />
                    </svg>
                </div>
                <h1>Lumi<span>Kids</span></h1>
                <div class="brand-rule"></div>
                <p>Sistema de Educação Infantil</p>
            </div>
        </aside>

        <main class="register-card">
            <div class="register-heading">
                <h2>Criar conta</h2>
                <p>Preencha seus dados para solicitar acesso.</p>
            </div>

            <div class="notice">
                Após o cadastro, sua conta ficará pendente de aprovação do administrador.
            </div>

            @if ($errors->any())
                <div class="error-box" role="alert">
                    <strong>Confira os dados informados:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="field">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Seu nome completo"
                        required>
                </div>

                <div class="field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="seu@gmail.com"
                        required>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Mínimo de 8 caracteres" required>
                    </div>
                    <div class="field">
                        <label for="senha_confirmation">Confirmar senha</label>
                        <input type="password" id="senha_confirmation" name="senha_confirmation"
                            placeholder="Repita a senha" required>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="perfil">Perfil</label>
                        <select id="perfil" name="perfil" required>
                            <option value="">Selecione</option>
                            <option value="professor" {{ old('perfil', request('perfil')) === 'professor' ? 'selected' : '' }}>Professor</option>
                            <option value="responsavel" {{ old('perfil', request('perfil')) === 'responsavel' ? 'selected' : '' }}>Responsável</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="escola_id">Escola</label>
                        <select id="escola_id" name="escola_id" required>
                            <option value="">Selecione sua escola</option>
                            @foreach($escolas as $escola)
                                <option value="{{ $escola->id }}" {{ old('escola_id') == $escola->id ? 'selected' : '' }}>
                                    {{ $escola->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    Criar conta
                    <span aria-hidden="true">&#8594;</span>
                </button>
            </form>

            <p class="login-link">Já possui uma conta? <a href="{{ route('login') }}">Entrar</a></p>
        </main>
    </div>
</body>

</html>