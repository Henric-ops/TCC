@extends('layout.app')

@section('title', 'Registro de ' . $registro->aluno->nome)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/registro-diario.css') }}">
@endpush

@section('content')
    <div class="registro-detail-header">
        <div class="registro-detail-header__content">
            <div class="registro-detail-avatar" aria-hidden="true">
                {{ strtoupper(substr($registro->aluno->nome, 0, 1)) }}
            </div>
            <div>
                <h1>{{ $registro->aluno->nome }}</h1>
                <div class="registro-detail-subtitle">
                    Registro de {{ $registro->data->format('d/m/Y') }} · Lançado por {{ $registro->professor->nome }}
                </div>
            </div>
        </div>
        <div class="registro-detail-date">
            <i class="bi bi-calendar3" aria-hidden="true"></i>
            {{ $registro->data->format('d/m/Y') }}
        </div>
    </div>

    <div class="registro-detail-grid">
        <div class="registro-detail-card">
            <div class="card-title"><span class="badge-icon green"><i class="bi bi-cup-hot-fill"></i></span> Alimentação
            </div>

            @php
                $refeicoes = ['colacao' => 'Colação', 'almoco' => 'Almoço', 'lanche' => 'Lanche', 'jantar' => 'Jantar'];
                $statusLabel = ['tudo' => 'Comeu tudo', 'parte' => 'Comeu parte', 'rejeitou' => 'Rejeitou'];
                $statusClasse = ['tudo' => 'ok', 'parte' => 'pendente', 'rejeitou' => 'falta'];
                $liquidos = ['leite' => 'Leite', 'suco' => 'Suco', 'agua' => 'Água'];
            @endphp

            @foreach($refeicoes as $chave => $nome)
                <div class="resumo-linha">
                    <span>{{ $nome }}</span>
                    @if(isset($alimentacaoMap[$chave]))
                        <span
                            class="status-pill {{ $statusClasse[$alimentacaoMap[$chave]] }}">{{ $statusLabel[$alimentacaoMap[$chave]] }}</span>
                    @else
                        <span class="status-pill pendente">Não registrado</span>
                    @endif
                </div>
            @endforeach

            <div class="subsection-title" style="margin-top:18px;">
                <span class="badge-icon blue" style="width:26px;height:26px;font-size:12px;"><i
                        class="bi bi-droplet-fill"></i></span> Líquidos
            </div>

            @foreach($liquidos as $chave => $nome)
                <div class="resumo-linha">
                    <span>{{ $nome }}</span>
                    @if(isset($liquidosMap[$chave]))
                        <span
                            class="status-pill {{ $statusClasse[$liquidosMap[$chave]] }}">{{ $statusLabel[$liquidosMap[$chave]] }}</span>
                    @else
                        <span class="status-pill pendente">Não registrado</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="registro-detail-card">
            <div class="card-title"><span class="badge-icon purple"><i class="bi bi-moon-stars-fill"></i></span> Sono</div>

            @if($registro->sono)
                <div class="resumo-linha">
                    <span>Dormiu?</span>
                    <span
                        class="status-pill {{ $registro->sono->dormiu ? 'ok' : 'falta' }}">{{ $registro->sono->dormiu ? 'Sim' : 'Não' }}</span>
                </div>

                @if($registro->sono->inicio_1)
                    <div class="resumo-linha">
                        <span>Período 1</span>
                        <span>{{ \Carbon\Carbon::parse($registro->sono->inicio_1)->format('H:i') }} às
                            {{ $registro->sono->fim_1 ? \Carbon\Carbon::parse($registro->sono->fim_1)->format('H:i') : '--' }}</span>
                    </div>
                @endif

                @if($registro->sono->inicio_2)
                    <div class="resumo-linha">
                        <span>Período 2</span>
                        <span>{{ \Carbon\Carbon::parse($registro->sono->inicio_2)->format('H:i') }} às
                            {{ $registro->sono->fim_2 ? \Carbon\Carbon::parse($registro->sono->fim_2)->format('H:i') : '--' }}</span>
                    </div>
                @endif
            @else
                <p class="text-muted">Sono não registrado nesse dia.</p>
            @endif
        </div>
    </div>

    <div class="registro-detail-card registro-detail-card--wide">
        <div class="card-title"><span class="badge-icon amber"><i class="bi bi-bandaid-fill"></i></span> Troca de fralda
        </div>

        <div class="resumo-linha">
            <span>Xixi</span>
            <span class="status-pill ok">{{ $xixiTotal }}x</span>
        </div>
        <div class="resumo-linha">
            <span>Cocô</span>
            <span class="status-pill ok">{{ $cocoTotal }}x</span>
        </div>

        @if($observacoesFralda)
            <div class="obs-label">Observações</div>
            <p>{{ $observacoesFralda }}</p>
        @endif
    </div>

    <div class="registro-detail-actions mt-3">
        <a href="{{ route('registros.meus') }}" class="back-button">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            Voltar ao histórico
        </a>
    </div>
@endsection