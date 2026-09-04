@extends('layout.app')

@section('title', 'Dashboard - Professor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="dash-greeting">Olá, {{ $user->nome }} 👋</div>
    <p class="dash-subtitle">Aqui está um resumo das suas turmas hoje.</p>

    @if($turmasSemFrequencia > 0)
        <div class="dash-alert">
            <div class="dash-alert-text">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $turmasSemFrequencia }}
                    {{ $turmasSemFrequencia === 1 ? 'turma ainda não teve' : 'turmas ainda não tiveram' }} frequência marcada
                    hoje.</span>
            </div>
            <a href="{{ route('frequencia.selecionar') }}" class="btn">Marcar agora</a>
        </div>
    @endif

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div class="stat-value">{{ $turmas->count() }}</div>
            <div class="stat-label">Minhas turmas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-person"></i></div>
            <div class="stat-value">{{ $totalAlunos }}</div>
            <div class="stat-label">Alunos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-clipboard-check"></i></div>
            <div class="stat-value">{{ $registrosHoje }}</div>
            <div class="stat-label">Registros feitos hoje</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-calendar-x"></i></div>
            <div class="stat-value">{{ $turmasSemFrequencia }}</div>
            <div class="stat-label">Turmas sem frequência hoje</div>
        </div>
    </div>

    <div class="dash-panel">
        <div class="dash-panel-header">
            <h2>Minhas turmas</h2>
            <a href="{{ route('turmas.minhas') }}">Ver todas</a>
        </div>

        @if($turmas->isEmpty())
            <div class="dash-empty">Você ainda não está vinculado a nenhuma turma.</div>
        @else
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Período</th>
                        <th>Alunos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($turmas as $turma)
                        <tr>
                            <td>{{ $turma->nome }}</td>
                            <td>{{ $turma->periodo }}</td>
                            <td>{{ $turma->alunos_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('registros.selecionar-aluno') }}" class="btn btn-sm btn-outline-primary">Registro
                                    diário</a>
                                <a href="{{ route('frequencia.form', ['turma_id' => $turma->id]) }}"
                                    class="btn btn-sm btn-outline-primary">Frequência</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection