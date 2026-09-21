@extends('layout.app')

@section('title', 'Registro Diário - Selecionar aluno')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/frequencia.css') }}">
    <link rel="stylesheet" href="{{ asset('css/turmas.css') }}">
@endpush

@section('content')
    <div class="frequencia-header">
        <div>
            <h1>Selecionar aluno</h1>
            <p class="text-muted mb-0">Turma: {{ $turma->nome }}</p>
        </div>

        <div class="frequencia-header-actions">
            <span class="frequencia-total">
                <strong>{{ $alunos->count() }}</strong>
                {{ $alunos->count() === 1 ? 'aluno' : 'alunos' }}
            </span>
            <a href="{{ route('registros.selecionar-turma') }}" class="freq-link freq-link-muted">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Trocar turma
            </a>
        </div>
    </div>

    <div class="card frequencia-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Aluno</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alunos as $aluno)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="turma-avatar" aria-hidden="true">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <strong>{{ $aluno->nome }}</strong>
                                </div>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('registros.create', ['aluno_id' => $aluno->id]) }}" class="frequencia-action">
                                    <i class="bi bi-journal-plus" aria-hidden="true"></i>
                                    <span>Fazer registro</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center py-5">
                                <div class="frequencia-empty">
                                    <i class="bi bi-person-x" aria-hidden="true"></i>
                                    <span>Nenhum aluno vinculado a essa turma.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection