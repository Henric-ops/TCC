@extends('layout.app')

@section('title', 'Turmas')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/turmas.css') }}">

    <div class="turmas-header d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">

            <div>
                <h1 class="h4 mb-1">Turmas</h1>
            </div>
        </div>

        <div class="turmas-header-actions d-flex align-items-center gap-3">
            <span class="turmas-total">
                <strong>{{ $turmas->total() }}</strong>
                {{ $turmas->total() === 1 ? 'turma' : 'turmas' }}
            </span>
            <a href="{{ route('admin.turmas.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                Nova turma
            </a>
        </div>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            {{ session('sucesso') }}
        </div>
    @endif

    <div class="card turmas-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-people" aria-hidden="true"></i> Nome</th>
                        <th><i class="bi bi-calendar3" aria-hidden="true"></i> Ano</th>
                        <th><i class="bi bi-clock" aria-hidden="true"></i> Período</th>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Alunos</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($turmas as $turma)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="turma-avatar" aria-hidden="true">
                                        <i class="bi bi-people-fill"></i>
                                    </span>
                                    <strong>{{ $turma->nome }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="turma-year">
                                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                                    {{ $turma->ano }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary-emphasis turma-badge">
                                    <i class="bi bi-clock" aria-hidden="true"></i>
                                    {{ $turma->periodo }}
                                </span>
                            </td>
                            <td>
                                <span class="turma-student-count">
                                    <i class="bi bi-person-fill" aria-hidden="true"></i>
                                    <strong>{{ $turma->alunos_count }}</strong>
                                    {{ $turma->alunos_count === 1 ? 'aluno' : 'alunos' }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.turmas.edit', $turma) }}" class="btn btn-sm btn-primary turma-action"
                                    title="Editar turma" aria-label="Editar turma">
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                    <span>Editar</span>
                                </a>
                                <form action="{{ route('admin.turmas.destroy', $turma) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Remover esta turma?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger turma-action" title="Excluir turma"
                                        aria-label="Excluir turma">
                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                        <span>Excluir</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="turma-empty">
                                    <i class="bi bi-people" aria-hidden="true"></i>
                                    <span>Nenhuma turma cadastrada ainda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $turmas->links() }}
    </div>
@endsection