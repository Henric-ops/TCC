@extends('layout.app')

@section('title', 'Alunos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/alunos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')

    <div class="alunos-header d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h4 mb-1">Alunos</h1>
        </div>

        <a href="{{ route('admin.alunos.create') }}" class="usuario-button usuario-button-primary">
            <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
            Novo aluno
        </a>

    </div>

    <form method="GET" class="freq-filtros alunos-filtros">

        <div class="freq-filtro-item alunos-filtro-item">
            <label for="busca">Aluno</label>

            <div class="alunos-busca">
                <i class="bi bi-search" aria-hidden="true"></i>

                <input type="text" id="busca" name="busca" value="{{ $busca }}" placeholder="Buscar aluno">
            </div>
        </div>

        <div class="freq-filtro-item alunos-filtro-item">
            <label for="escola_id">Escola</label>

            <select name="escola_id" id="escola_id" class="alunos-filtro-escola">
                <option value="">Todas as escolas</option>

                @foreach($escolasPermitidas as $escola)
                    <option value="{{ $escola->id }}" {{ (string) $escolaId === (string) $escola->id ? 'selected' : '' }}>
                        {{ $escola->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="freq-filtro-item alunos-filtro-item">
            <label for="turma_id">Turma</label>

            <select name="turma_id" id="turma_id">
                <option value="">Todas as turmas</option>

                @foreach($turmasPermitidas as $turma)
                    <option value="{{ $turma->id }}" {{ (string) $turmaId === (string) $turma->id ? 'selected' : '' }}>
                        {{ $turma->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="usuario-button usuario-button-primary alunos-filtro-btn">
            <i class="bi bi-search" aria-hidden="true"></i>
            Buscar
        </button>

        <a href="{{ route('admin.alunos.index') }}" class="usuario-button usuario-button-muted alunos-filtro-btn">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
            Limpar
        </a>

    </form>

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
                        <th>
                            <i class="bi bi-person" aria-hidden="true"></i>
                            Aluno
                        </th>

                        <th>
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                            Idade
                        </th>

                        <th>
                            <i class="bi bi-people" aria-hidden="true"></i>
                            Turma
                        </th>

                        <th class="text-end">
                            Ações
                        </th>
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

                                    <div>
                                        <strong>{{ $aluno->nome }}</strong>

                                        <small class="d-block text-muted">
                                            Responsável:
                                            {{ $aluno->responsaveis->pluck('nome')->join(', ') ?: 'Sem responsável' }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            <td>
                                <span class="badge bg-warning-subtle text-warning-emphasis aluno-badge">
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

                                    <span>
                                        Nenhum aluno encontrado.
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
        {{ $alunos->links() }}
    </div>

@endsection