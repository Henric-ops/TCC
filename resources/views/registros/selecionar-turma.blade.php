@extends('layout.app')

@section('title', 'Registro Diário - Selecionar turma')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/frequencia.css') }}">
@endpush

@section('content')
    <div class="frequencia-header">
        <h1>Registro diário</h1>

        <div class="frequencia-header-actions">
            <span class="frequencia-total">
                <strong>{{ $turmas->count() }}</strong>
                {{ $turmas->count() === 1 ? 'turma' : 'turmas' }}
            </span>
        </div>
    </div>

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
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="turma-avatar" aria-hidden="true">
                                        <i class="bi bi-people-fill"></i>
                                    </span>
                                    <strong>{{ $turma->nome }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary-emphasis turma-badge">
                                    <i class="bi bi-clock" aria-hidden="true"></i>
                                    {{ $turma->periodo }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
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
                                    <span>Nenhuma turma disponível.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection