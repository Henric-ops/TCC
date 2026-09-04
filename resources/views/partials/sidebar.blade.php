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

            @if(in_array(auth()->user()->perfil, ['admin', 'professor']))
                <a href="{{ route('registros.index') }}"
                    class="lk-nav-item {{ request()->routeIs('registros.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i> Registros Diários
                </a>
            @endif

            @if(auth()->user()->perfil === 'responsavel')
                <a href="{{ route('registros.meus') }}"
                    class="lk-nav-item {{ request()->routeIs('registros.meus') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i> Registros
                </a>
            @endif
            @if(in_array(auth()->user()->perfil, ['admin', 'professor']))
                <a href="{{ route('frequencia.selecionar') }}"
                    class="lk-nav-item {{ request()->routeIs('frequencia.selecionar') || request()->routeIs('frequencia.form') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Frequência
                </a>
            @endif

            @if(auth()->user()->perfil === 'responsavel')
                <a href="{{ route('frequencia.meus') }}"
                    class="lk-nav-item {{ request()->routeIs('frequencia.meus') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Frequência
                </a>
            @endif
        </div>
        <div class="lk-nav-section">
            <span class="lk-nav-label">Comunicação</span>

            @if(in_array(auth()->user()->perfil, ['admin', 'professor']))
                <a href="{{ route('emails.create') }}"
                    class="lk-nav-item {{ request()->routeIs('emails.create') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i> Comunicados
                </a>
            @endif


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