@extends('layout.app')

@section('title', $turma->nome)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/turmas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="turmas-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $turma->nome }}</h1>
            <span class="badge bg-primary-subtle text-primary-emphasis turma-badge">
                <i class="bi bi-clock" aria-hidden="true"></i>
                {{ $turma->periodo }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="turmas-total">
                <strong>{{ $turma->alunos->count() }}</strong>
                {{ $turma->alunos->count() === 1 ? 'aluno' : 'alunos' }}
            </span>
            <a href="{{ route('turmas.minhas') }}" class="usuario-button usuario-button-muted">Voltar</a>
        </div>
    </div>

    <div class="card turmas-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Aluno</th>
                        <th><i class="bi bi-calendar3" aria-hidden="true"></i> Idade</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($turma->alunos as $aluno)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="turma-avatar" aria-hidden="true">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <strong>{{ $aluno->nome }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="turma-year">
                                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                                    {{ $aluno->data_nascimento->age }} anos
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('registros.create', ['aluno_id' => $aluno->id]) }}"
                                    class="usuario-button usuario-button-primary">Registro diário</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="turma-empty">
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