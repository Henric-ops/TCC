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
        <div class="stat-icon orange"><i class="bi bi-alarm"></i></div>
        <div class="stat-value">{{ $dados['mediaSonoFormatada'] }}</div>
        <div class="stat-label">Média de sono por dia</div>
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
        <h2>Presença no período</h2>
    </div>

    <div class="calendario-presenca">
        @foreach($dados['diasPeriodo'] as $dia)
            <div class="dia-chip dia-{{ $dia['status'] }}"
                title="{{ $dia['data']->format('d/m/Y') }} — {{ $dia['status'] === 'presente' ? 'Presente' : ($dia['status'] === 'falta' ? 'Falta' : 'Sem registro') }}">
                {{ $dia['data']->format('d') }}
            </div>
        @endforeach
    </div>

    <div class="calendario-legenda">
        <span><i class="dia-chip-mini dia-presente"></i> Presente</span>
        <span><i class="dia-chip-mini dia-falta"></i> Falta</span>
        <span><i class="dia-chip-mini dia-sem-registro"></i> Sem registro</span>
    </div>
</div>