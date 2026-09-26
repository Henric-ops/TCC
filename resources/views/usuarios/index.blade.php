@extends('layout.app')

@section('title', 'Usuários')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/tabela.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alunos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">

    <div class="usuarios-header d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h4 mb-1">Usuários</h1>
        </div>

        <div class="usuarios-header-actions d-flex align-items-center gap-3">
            <a href="{{ route('admin.usuarios.create') }}" class="usuario-button usuario-button-primary">

                <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                Novo usuário

            </a>
        </div>

    </div>


    @if(session('sucesso'))

        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">

            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>

            {{ session('sucesso') }}

        </div>

    @endif


    @if(session('erro'))

        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">

            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>

            {{ session('erro') }}

        </div>

    @endif



    <div class="usuarios-filtros">


        <form action="{{ route('admin.usuarios.index') }}" method="GET" class="usuarios-filtro-form freq-filtros">

            @if(request('perfil'))
                <input type="hidden" name="perfil" value="{{ request('perfil') }}">
            @endif

            <div class="freq-filtro-item alunos-filtro-item">
                <label for="busca">Usuário</label>

                <div class="alunos-busca">

                    <i class="bi bi-search" aria-hidden="true"></i>

                    <input type="text" id="busca" name="busca" value="{{ request('busca') }}"
                        placeholder="Buscar por nome ou e-mail...">

                </div>
            </div>



            <div class="freq-filtro-item alunos-filtro-item">
                <label for="status">Status</label>

                <select name="status" id="status" class="usuarios-filtro-select" onchange="this.form.submit()">

                    <option value="">Todos os status</option>

                    <option value="aprovado" {{ request('status') === 'aprovado' ? 'selected' : '' }}>
                        Aprovados
                    </option>

                    <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>
                        Pendentes
                    </option>

                    <option value="recusado" {{ request('status') === 'recusado' ? 'selected' : '' }}>
                        Recusados
                    </option>

                </select>
            </div>


            <button type="submit" class="usuario-button usuario-button-primary alunos-filtro-btn">
                <i class="bi bi-search"></i>
                Buscar
            </button>


            <a href="{{ route('admin.usuarios.index') }}" class="usuario-button usuario-button-muted alunos-filtro-btn"
                aria-label="Limpar filtros">

                <i class="bi bi-x-lg"></i>
                Limpar

            </a>

        </form>

    </div>



    <div class="usuarios-tabs">

        <a href="{{ route('admin.usuarios.index', request()->except('perfil', 'page')) }}"
            class="usuarios-tab {{ !request('perfil') ? 'active' : '' }}">

            <i class="bi bi-people"></i>

            Todos

            <span>{{ $totalUsuarios }}</span>

        </a>


        <a href="{{ route('admin.usuarios.index', array_merge(request()->except('perfil', 'page'), ['perfil' => 'professor'])) }}"
            class="usuarios-tab {{ request('perfil') === 'professor' ? 'active' : '' }}">

            <i class="bi bi-mortarboard-fill"></i>

            Professores

            <span>{{ $totalProfessores }}</span>

        </a>


        <a href="{{ route('admin.usuarios.index', array_merge(request()->except('perfil', 'page'), ['perfil' => 'responsavel'])) }}"
            class="usuarios-tab {{ request('perfil') === 'responsavel' ? 'active' : '' }}">

            <i class="bi bi-person-heart"></i>

            Responsáveis

            <span>{{ $totalResponsaveis }}</span>

        </a>

    </div>


    <div class="card usuarios-table-card">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>
                            <i class="bi bi-person" aria-hidden="true"></i>
                            Nome
                        </th>

                        <th>
                            <i class="bi bi-envelope" aria-hidden="true"></i>
                            E-mail
                        </th>

                        <th>
                            <i class="bi bi-person-badge" aria-hidden="true"></i>
                            Perfil
                        </th>

                        <th>
                            <i class="bi bi-activity" aria-hidden="true"></i>
                            Status
                        </th>

                        <th class="text-end">
                            Ações
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($usuarios as $usuario)

                        <tr>

                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <span class="usuario-avatar" aria-hidden="true">
                                        {{ strtoupper(substr($usuario->nome, 0, 1)) }}
                                    </span>

                                    <strong>
                                        {{ $usuario->nome }}
                                    </strong>

                                </div>

                            </td>


                            <td class="usuario-email">

                                <i class="bi bi-envelope me-1" aria-hidden="true"></i>

                                {{ $usuario->email }}

                            </td>



                            <td>

                                @if($usuario->perfil === 'professor')

                                    <span class="badge bg-info-subtle text-info-emphasis usuario-badge">

                                        <i class="bi bi-mortarboard-fill" aria-hidden="true"></i>

                                        Professor

                                    </span>

                                @elseif($usuario->perfil === 'responsavel')

                                    <span class="badge bg-secondary-subtle text-secondary-emphasis usuario-badge">

                                        <i class="bi bi-person-heart" aria-hidden="true"></i>

                                        Responsável

                                    </span>

                                @endif

                            </td>



                            <td>

                                @if($usuario->status === 'pendente')

                                    <span class="badge bg-warning-subtle text-warning-emphasis usuario-badge">

                                        <i class="bi bi-hourglass-split" aria-hidden="true"></i>

                                        Pendente

                                    </span>

                                @elseif($usuario->status === 'aprovado')

                                    <span class="badge bg-success-subtle text-success-emphasis usuario-badge">

                                        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>

                                        Aprovado

                                    </span>

                                @elseif($usuario->status === 'recusado')

                                    <span class="badge bg-danger-subtle text-danger-emphasis usuario-badge">

                                        <i class="bi bi-x-circle-fill" aria-hidden="true"></i>

                                        Recusado

                                    </span>

                                @endif

                            </td>



                            <td class="text-end text-nowrap">

                                <a href="{{ route('admin.usuarios.edit', $usuario) }}"
                                    class="usuario-action usuario-action-primary" title="Editar usuário"
                                    aria-label="Editar usuário">

                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>

                                    <span>Editar</span>

                                </a>


                                @if($usuario->status === 'pendente')

                                    <a href="{{ route('admin.usuarios.verificar', $usuario) }}"
                                        class="usuario-action usuario-action-primary" title="Validar cadastro"
                                        aria-label="Validar cadastro">

                                        <i class="bi bi-person-check-fill" aria-hidden="true"></i>

                                        <span>Validar</span>

                                    </a>

                                @endif


                                <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Remover este usuário?')">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" class="usuario-action usuario-action-danger" title="Excluir usuário"
                                        aria-label="Excluir usuário">

                                        <i class="bi bi-trash3" aria-hidden="true"></i>

                                        <span>Excluir</span>

                                    </button>

                                </form>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="5" class="text-center py-5">

                                <div class="usuario-empty">

                                    <i class="bi bi-people" aria-hidden="true"></i>

                                    <span>
                                        Nenhum usuário encontrado.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    <div class="mt-3">

        {{ $usuarios->links() }}

    </div>

@endsection