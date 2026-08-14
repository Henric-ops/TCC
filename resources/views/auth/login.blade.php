<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LumiKids - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
    </style>
</head>

<body>
    <div class="card">
        <div class="logo-wrap"> <!-- Logo SVG -->
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 24 L16 30 L22 32 L16 34 L14 40 L12 34 L6 32 L12 30 Z" fill="#a06bf2" />
                <path d="M84 18 L86 24 L92 26 L86 28 L84 34 L82 28 L76 26 L82 24 Z" fill="#f5b800" />
                <path d="M12 66 L13.5 70.5 L18 72 L13.5 73.5 L12 78 L10.5 73.5 L6 72 L10.5 70.5 Z" fill="#2dd4bf" />
                <path d="M88 68 L89.5 72 L93 73.5 L89.5 75 L88 79 L86.5 75 L83 73.5 L86.5 72 Z" fill="#f472b6" />
                <path d="M50 14
               L58.5 36.5
               L82 38.5
               L64 54
               L69.5 77
               L50 64.5
               L30.5 77
               L36 54
               L18 38.5
               L41.5 36.5 Z" stroke="#4a78d6" stroke-width="5.5" stroke-linejoin="round" fill="#fff" />
                <circle cx="42" cy="53" r="3" fill="#1b1f2a" />
                <circle cx="58" cy="53" r="3" fill="#1b1f2a" />
                <path d="M42 61 Q50 68 58 61" stroke="#1b1f2a" stroke-width="3" stroke-linecap="round" fill="none" />
            </svg>
        </div>
        <!-- Logo SVG -->

        <h1 class="brand">LumiKids</h1>
        <p class="tagline">Sistema de Educação Infantil</p>

        @if ($errors->any())
            <div class="alert-error">
                <strong>Erro:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
            @csrf

            <div class="field">
                <label for="email">E-mail</label>
                <div class="input-wrap">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2" />
                        <path d="m2 7 10 6 10-6" />
                    </svg>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="seu@gmail.com" required>
                </div>
            </div>

            <div class="field">
                <label for="senha">Senha</label>
                <div class="input-wrap">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="10" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                    <button type="button" class="toggle-pass" id="togglePass" aria-label="Mostrar senha">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="forgot"><a href="#">Esqueci a senha</a></div>

            <button type="submit" class="btn-primary">
                Entrar
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </button>
        </form>

        <div class="divider">
            <div class="line"></div><span>ou</span>
            <div class="line"></div>
        </div>

        <p class="signup-title">Não possui conta?</p>
        <p class="signup-sub">Escolha um tipo de cadastro:</p>

        <div class="role-options">
            <button type="button" class="role-btn" data-role="responsavel">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <span class="role-text">Sou<br>Responsável</span>
            </button>
            <button type="button" class="role-btn active" data-role="professor">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M22 10 12 5 2 10l10 5 10-5Z" />
                    <path d="M6 12v5c0 1.5 2.5 3 6 3s6-1.5 6-3v-5" />
                </svg>
                <span class="role-text">Sou<br>Professor</span>
            </button>
        </div>

    </div>

    <script>
        const toggleBtn = document.getElementById('togglePass');
        const senhaInput = document.getElementById('senha');
        toggleBtn.addEventListener('click', () => {
            const show = senhaInput.type === 'password';
            senhaInput.type = show ? 'text' : 'password';
            toggleBtn.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
        });

        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });

        document.getElementById('loginForm').addEventListener('submit', () => {
            // O envio do formulário é processado pelo Laravel via POST.
        });
    </script>

</body>

</html>