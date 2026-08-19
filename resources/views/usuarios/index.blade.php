@extends('layout.app')

@section('title', 'Usuários')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">Usuários</h1>

        </div>

        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Novo usuário
        </a>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success">
            {{ session('sucesso') }}
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($usuarios as $usuario)

                        <tr>

                            <td>
                                <strong>{{ $usuario->nome }}</strong>
                            </td>

                            <td>
                                {{ $usuario->email }}
                            </td>

                            <td>
                                @if($usuario->perfil === 'professor')
                                    <span class="badge bg-info text-dark">
                                        Professor
                                    </span>
                                @elseif($usuario->perfil === 'responsavel')
                                    <span class="badge bg-secondary">
                                        Responsável
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($usuario->status === 'pendente')
                                    <span class="badge bg-warning text-dark">
                                        Pendente
                                    </span>

                                @elseif($usuario->status === 'aprovado')
                                    <span class="badge bg-success">
                                        Aprovado
                                    </span>

                                @elseif($usuario->status === 'recusado')
                                    <span class="badge bg-danger">
                                        Recusado
                                    </span>
                                @endif
                            </td>


                            <td class="text-end">

                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-primary">
                                    Editar
                                </a>

                                <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Remover este usuário?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Excluir
                                    </button>

                                    <a href="{{ route('admin.usuarios.verificar', $usuario) }}" class="btn btn-sm btn-success">
                                        Verificar
                                    </a>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Nenhum usuário cadastrado ainda.
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