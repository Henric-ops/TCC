@extends('layout.app')

@section('title', 'Registro Diário - Selecionar turma')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/frequencia.css') }}">
@endpush

@section('content')
    <div class="frequencia-header">
        <h1>Registro diário</h1>
    </div>

    <form method="GET" class="freq-filtros mb-4">
        <div class="freq-filtro-item">
            <label for="escola_id">Escola</label>
            <select name="escola_id" id="escola_id" onchange="this.form.submit()">
                <option value="">Todas as escolas</option>
                @foreach($escolasPermitidas as $escola)
                    <option value="{{ $escola->id }}" {{ (string) $escolaId === (string) $escola->id ? 'selected' : '' }}>
                        {{ $escola->nome }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="card frequencia-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-people" aria-hidden="true"></i> Turma</th>
                        <th><i class="bi bi-clock" aria-hidden="true"></i> Período</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($turmas as $turma)
                        <tr>
                            <td data-label="Turma">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="turma-avatar" aria-hidden="true">
                                        <i class="bi bi-people-fill"></i>
                                    </span>
                                    <strong>{{ $turma->nome }}</strong>
                                </div>
                            </td>
                            <td data-label="Período">
                                <span class="badge bg-primary-subtle text-primary-emphasis turma-badge">
                                    <i class="bi bi-clock" aria-hidden="true"></i>
                                    {{ $turma->periodo }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap" data-label="Ação">
                                <a href="{{ route('registros.selecionar-aluno', ['turma_id' => $turma->id]) }}"
                                    class="frequencia-action">
                                    <i class="bi bi-person-lines-fill" aria-hidden="true"></i>
                                    <span>Ver alunos</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="frequencia-empty">
                                    <i class="bi bi-clipboard2-x" aria-hidden="true"></i>
                                    <span>Nenhuma turma disponível para esta escola.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection