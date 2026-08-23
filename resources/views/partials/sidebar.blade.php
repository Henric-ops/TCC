<aside class="lk-sidebar">
    <div class="lk-sidebar-header">
        <img src="{{ asset('img/LogoSemFundo.png') }}" alt="Logo LumiKids" class="lk-logo-image">
        <div>
            <div class="lk-logo-title">LumiKids</div>
            <div class="lk-logo-subtitle">Gestão Escolar</div>
        </div>
    </div>

    <nav>
        <div class="lk-nav-section">
            <span class="lk-nav-label">Principal</span>

            <a href="{{ route('dashboard') }}"
                class="lk-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <a href="{{ route('admin.turmas.index') }}"
                class="lk-nav-item {{ request()->routeIs('admin.turmas.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Turmas
            </a>
            <a href="{{ route('admin.alunos.index') }}"
                class="lk-nav-item {{ request()->routeIs('admin.alunos.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> Alunos
            </a>

            @if(auth()->user()?->perfil === 'admin')
                <a href="{{ route('admin.usuarios.index') }}"
                    class="lk-nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> Usuários
                </a>
            @endif

            <a href="{{ url('/registros-diarios') }}"
                class="lk-nav-item {{ request()->is('registros-diarios*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i> Registros Diários
            </a>
            <a href="{{ url('/frequencia') }}" class="lk-nav-item {{ request()->is('frequencia*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Frequência
            </a>
        </div>

        <div class="lk-nav-section">
            <span class="lk-nav-label">Comunicação</span>

            <a href="{{ url('/comunicados') }}" class="lk-nav-item {{ request()->is('comunicados*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text"></i> Comunicados
            </a>
            <a href="{{ url('/mensagens') }}" class="lk-nav-item {{ request()->is('mensagens*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Mensagens
                @if(($mensagensNaoLidas ?? 0) > 0)
                    <span class="lk-badge">{{ $mensagensNaoLidas }}</span>
                @endif
            </a>
        </div>

        <div class="lk-nav-section">
            <span class="lk-nav-label">Análise</span>

            <a href="{{ url('/relatorios') }}" class="lk-nav-item {{ request()->is('relatorios*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Relatórios
            </a>
        </div>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="lk-logout-form">
        @csrf
        <button type="submit" class="lk-nav-item lk-logout-button">
            <i class="bi bi-box-arrow-right"></i> Sair
        </button>
    </form>
</aside>