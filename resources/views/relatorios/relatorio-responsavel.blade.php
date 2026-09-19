@extends('layout.app')

@section('title', 'Relatório')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h4 mb-0">Relatório</h1>
        <a href="{{ route('relatorio.meu.pdf', request()->query()) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-download"></i> Baixar PDF
        </a>
    </div>

    <form method="GET" class="card p-3 mb-4 d-flex flex-row flex-wrap gap-3 align-items-end">
        @if($filhos->count() > 1)
            <div>
                <label class="form-label small mb-1">Filho</label>
                <select name="aluno_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($filhos as $filho)
                        <option value="{{ $filho->id }}" {{ $filho->id === $aluno->id ? 'selected' : '' }}>{{ $filho->nome }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <input type="hidden" name="aluno_id" value="{{ $aluno->id }}">
        @endif

        <div>
            <label class="form-label small mb-1">De</label>
            <input type="date" name="inicio" value="{{ $inicio }}" class="form-control form-control-sm"
                onchange="this.form.submit()">
        </div>
        <div>
            <label class="form-label small mb-1">Até</label>
            <input type="date" name="fim" value="{{ $fim }}" class="form-control form-control-sm"
                onchange="this.form.submit()">
        </div>
    </form>

    <h2 class="h5 mb-3">{{ $aluno->nome }}</h2>

    <div class="stat-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-value">{{ $dados['percentualPresenca'] }}%</div>
            <div class="stat-label">Presença no período</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-clipboard-check"></i></div>
            <div class="stat-value">{{ $dados['totalRegistros'] }}</div>
            <div class="stat-label">Registros diários</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-moon-stars"></i></div>
            <div class="stat-value">{{ $dados['diasComSono'] }}</div>
            <div class="stat-label">Dias que dormiu</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-droplet"></i></div>
            <div class="stat-value">{{ $dados['totalXixi'] + $dados['totalCoco'] }}</div>
            <div class="stat-label">Trocas de fralda</div>
        </div>
    </div>

    <div class="dash-panel mb-4">
        <div class="dash-panel-header">
            <h2>Frequência</h2>
        </div>
        <div class="numeros-lista">
            <div class="numero-item"><span>Dias letivos no período</span><strong>{{ $dados['totalDias'] }}</strong></div>
            <div class="numero-item"><span>Presenças</span><strong>{{ $dados['presencas'] }}</strong></div>
            <div class="numero-item"><span>Faltas</span><strong>{{ $dados['faltas'] }}</strong></div>
        </div>
    </div>

    <div class="dash-panel">
        <div class="dash-panel-header">
            <h2>Alimentação</h2>
        </div>
        <div class="numeros-lista">
            <div class="numero-item"><span>Comeu tudo</span><strong>{{ $dados['alimentacaoContagem']['tudo'] }}</strong>
            </div>
            <div class="numero-item"><span>Comeu parte</span><strong>{{ $dados['alimentacaoContagem']['parte'] }}</strong>
            </div>
            <div class="numero-item"><span>Rejeitou</span><strong>{{ $dados['alimentacaoContagem']['rejeitou'] }}</strong>
            </div>
        </div>
    </div>
@endsection