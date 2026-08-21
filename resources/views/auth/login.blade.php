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
</head>

<body>
    <div class="login-page">


        <aside class="login-side">
            <svg class="wave-divider" viewBox="0 0 100 800" preserveAspectRatio="none">
                <path d="M100,0 C40,100 100,200 60,300 C20,400 90,500 50,600 C10,700 80,750 100,800 L100,0 Z"
                    fill="#fff" />
            </svg>

            <svg class="decor-sparkle s1" viewBox="0 0 24 24" fill="#F4B942">
                <path d="M12 0 L14 10 L24 12 L14 14 L12 24 L10 14 L0 12 L10 10 Z" />
            </svg>
            <svg class="decor-sparkle s2" viewBox="0 0 24 24" fill="#8B7CF6">
                <path d="M12 0 L14 10 L24 12 L14 14 L12 24 L10 14 L0 12 L10 10 Z" />
            </svg>
            <svg class="decor-sparkle s3" viewBox="0 0 24 24" fill="#35BFA1">
                <path d="M12 0 L14 10 L24 12 L14 14 L12 24 L10 14 L0 12 L10 10 Z" />
            </svg>
            <svg class="decor-sparkle s4" viewBox="0 0 24 24" fill="#4C63E8">
                <path d="M12 0 L14 10 L24 12 L14 14 L12 24 L10 14 L0 12 L10 10 Z" />
            </svg>

            <div class="brand-top">
                <div class="brand-logo" aria-hidden="true">
                    <svg viewBox="0 0 48 48" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M4 18 L24 8 L44 18 L24 28 Z" />
                        <path d="M13 22.5 V33 C13 33 17 38 24 38 C31 38 35 33 35 33 V22.5" />
                        <path d="M44 18 V29" />
                        <circle cx="44" cy="32.5" r="2" fill="#fff" stroke="none" />
                    </svg>
                </div>

                <h1 class="brand-title">Lumi<span class="accent">Kids</span></h1>
                <div class="brand-rule"></div>
                <p class="brand-subtitle">Sistema de Educação Infantil</p>
            </div>

            <div class="illustration-cloud"></div>
        </aside>


        <main class="login-main">
            <h2 class="welcome-title">Acesse sua conta </h2>
            <p class="welcome-sub">Faça login para continuar</p>

            @if (session('sucesso'))
                <div class="alert-success" role="status">
                    {{ session('sucesso') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
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
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="seu@email.com" required>
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

            <p class="signup-title">Não possui conta ainda?</p>
            <a href="{{ route('register') }}" class="btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M19 8v6M22 11h-6" />
                </svg>
                Criar conta
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </a>

        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePass');
        const senhaInput = document.getElementById('senha');
        toggleBtn.addEventListener('click', () => {
            const show = senhaInput.type === 'password';
            senhaInput.type = show ? 'text' : 'password';
            toggleBtn.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
        });
    </script>
</body>

</html>