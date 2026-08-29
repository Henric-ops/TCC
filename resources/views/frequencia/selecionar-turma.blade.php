@extends('layout.app')

@section('title', 'Frequência - Selecionar turma')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/frequencia.css') }}">
@endpush

@section('content')
    <div class="frequencia-header">
        <h1>Frequência</h1>

        <div class="frequencia-header-actions">
            <span class="frequencia-total">
                <strong>{{ $turmas->count() }}</strong>
                {{ $turmas->count() === 1 ? 'turma' : 'turmas' }}
            </span>
        </div>
    </div>

    @if(session('erro'))
        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            {{ session('erro') }}
        </div>
    @endif

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
                                <a href="{{ route('frequencia.form', ['turma_id' => $turma->id]) }}"
                                    class="btn btn-sm btn-primary frequencia-action">
                                    <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                    <span>Marcar frequência</span>
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

    <div class="mt-3">
        <a href="{{ route('frequencia.index') }}" class="freq-link">Ver histórico de frequência</a>
    </div>
@endsection