@extends('layout.app')

@section('title', 'Alunos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/alunos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="alunos-header d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <h1 class="h4 mb-1">Alunos</h1>
        </div>

        <div class="alunos-header-actions d-flex align-items-center gap-3">
            <a href="{{ route('admin.alunos.create') }}" class="usuario-button usuario-button-primary">
                <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                Novo aluno
            </a>
        </div>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            {{ session('sucesso') }}
        </div>
    @endif

    <div class="card alunos-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Nome</th>
                        <th><i class="bi bi-calendar3" aria-hidden="true"></i> Idade</th>
                        <th><i class="bi bi-people" aria-hidden="true"></i> Turmas</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alunos as $aluno)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="aluno-avatar" aria-hidden="true">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <strong>{{ $aluno->nome }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-warning-subtle text-warning-emphasis aluno-badge">
                                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                                    {{ $aluno->data_nascimento->age }} anos
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success-emphasis aluno-badge">
                                    <i class="bi bi-people" aria-hidden="true"></i>
                                    {{ $aluno->turmas->pluck('nome')->join(', ') ?: 'Sem turma' }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.alunos.edit', $aluno) }}" class="usuario-action usuario-action-primary"
                                    title="Editar aluno" aria-label="Editar aluno">
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                    <span>Editar</span>
                                </a>
                                <form action="{{ route('admin.alunos.destroy', $aluno) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Remover este aluno?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="usuario-action usuario-action-danger" title="Excluir aluno"
                                        aria-label="Excluir aluno">
                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                        <span>Excluir</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="aluno-empty">
                                    <i class="bi bi-people" aria-hidden="true"></i>
                                    <span>Nenhum aluno cadastrado ainda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $alunos->links() }}
    </div>
@endsection