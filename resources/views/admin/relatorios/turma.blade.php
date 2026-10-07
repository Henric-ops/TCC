@extends('layout.app')

@section('title', 'Relatório - ' . $turma->nome)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h1 class="h4 mb-0">{{ $turma->nome }}</h1>
        <a href="{{ route('admin.relatorios.index') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
    </div>
    <p class="text-muted small mb-4">Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} a
        {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
    </p>

    <div class="stat-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-value">{{ $mediaPresencaTurma }}%</div>
            <div class="stat-label">Presença média da turma</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-people"></i></div>
            <div class="stat-value">{{ $linhas->count() }}</div>
            <div class="stat-label">Alunos</div>
        </div>
    </div>

    <div class="dash-panel">
        <div class="dash-panel-header">
            <h2>Por aluno</h2>
        </div>

        @if($linhas->isEmpty())
            <div class="dash-empty">Essa turma não tem alunos vinculados.</div>
        @else
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Presença</th>
                        <th>Faltas</th>
                        <th>Registros diários</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($linhas as $linha)
                        <tr>
                            <td>{{ $linha['aluno']->nome }}</td>
                            <td>{{ $linha['percentualPresenca'] }}%</td>
                            <td>{{ $linha['faltas'] }}</td>
                            <td>{{ $linha['totalRegistros'] }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.relatorios.aluno', ['aluno_id' => $linha['aluno']->id, 'inicio' => $inicio, 'fim' => $fim]) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Ver individual
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection