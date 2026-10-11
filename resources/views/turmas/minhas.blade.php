@extends('layout.app')

@section('title', 'Minhas Turmas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/turmas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alunos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="turmas-header d-flex justify-content-between align-items-center mb-4">
        <h1>Minhas Turmas</h1>
    </div>

    <form method="GET" class="freq-filtros alunos-filtros mb-4">
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

        <button type="submit" class="usuario-button usuario-button-primary alunos-filtro-btn">
            <i class="bi bi-search" aria-hidden="true"></i>
            Buscar
        </button>

        <a href="{{ route('turmas.minhas') }}" class="usuario-button usuario-button-muted alunos-filtro-btn">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
            Limpar
        </a>
    </form>

    <div class="card turmas-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ano</th>
                        <th>Período</th>
                        <th>Alunos</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($turmasPorEscola as $turmasDaEscola)

                        @foreach($turmasDaEscola as $turma)
                            <tr>
                                <td><strong>{{ $turma->nome }}</strong></td>
                                <td>{{ $turma->ano }}</td>
                                <td>{{ $turma->periodo }}</td>
                                <td>{{ $turma->alunos_count }} {{ $turma->alunos_count === 1 ? 'aluno' : 'alunos' }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('turmas.minha', $turma) }}" class="usuario-button usuario-button-primary"
                                        title="Ver alunos" aria-label="Ver alunos">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="turma-empty">
                                    <i class="bi bi-people" aria-hidden="true"></i>
                                    <span>Você ainda não está vinculado a nenhuma turma.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection