@extends('layout.app')

@section('title', 'Selecionar aluno')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/registro-diario.css') }}">

    <div class="alunos-header d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div>
                <h1 class="h4 mb-1">Selecione o aluno</h1>
            </div>
        </div>

        <div class="alunos-header-actions d-flex align-items-center gap-3">
            <span class="alunos-total">
                <strong>{{ $alunos->count() }}</strong>
                {{ $alunos->count() === 1 ? 'aluno' : 'alunos' }}
            </span>
        </div>
    </div>

    @if(session('erro'))
        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            {{ session('erro') }}
        </div>
    @endif

    <div class="card alunos-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Nome do aluno</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alunos as $aluno)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="aluno-avatar" aria-hidden="true">
                                        {{ strtoupper(substr($aluno->nome, 0, 1)) }}
                                    </span>
                                    <strong>{{ $aluno->nome }}</strong>
                                </div>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('registros.create', ['aluno_id' => $aluno->id]) }}"
                                    class="btn btn-sm btn-primary aluno-action" title="Fazer registro"
                                    aria-label="Fazer registro">
                                    <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                    <span>Fazer registro</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center py-5">
                                <div class="aluno-empty">
                                    <i class="bi bi-inbox" aria-hidden="true"></i>
                                    <span>Nenhum aluno disponível.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection