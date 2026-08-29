@extends('layout.app')

@section('title', 'Editar registro diário')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/registro-diario.css') }}">
@endpush

@section('content')
    <form method="POST" action="{{ route('registros.update', $registro) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="aluno_id" value="{{ $registro->aluno_id }}">

        <div class="header-card">
            <div>
                <h1>{{ $registro->aluno->nome }}</h1>
                <div class="sub">{{ $registro->aluno->data_nascimento->age }} anos · Turma {{ $registro->aluno->turmas->pluck('nome')->join(', ') ?: '—' }} · {{ $registro->data->format('d/m/Y') }}</div>
            </div>
            <div class="date-pill"><i class="bi bi-calendar3"></i> {{ $registro->data->format('d/m/Y') }}</div>
        </div>

        <div class="grid-top">
            <div class="card">
                <div class="card-title"><span class="badge-icon green"><i class="bi bi-cup-hot-fill"></i></span> ALIMENTAÇÃO</div>

                <div class="feed-row">
                    <div>
                        <div class="period-label"><i class="bi bi-sun-fill text-warning"></i> Manhã</div>
                        <div class="col-headers"><span></span><span>TUDO</span><span>PARTE</span><span>REJEITOU</span></div>
                        @foreach(['colacao' => 'Colação', 'almoco' => 'Almoço'] as $chave => $nome)
                            <div class="meal-row"><span>{{ $nome }}</span>
                                @foreach(['tudo' => 'TUDO', 'parte' => 'PARTE', 'rejeitou' => 'REJEITOU'] as $status => $rotulo)
                                    <label class="radio {{ ($alimentacaoAtual[$chave] ?? '') === $status ? 'selected' : '' }}" title="{{ $rotulo }}">
                                        <input class="choice-input" type="radio" name="alimentacao[{{ $chave }}]" value="{{ $status }}" {{ ($alimentacaoAtual[$chave] ?? '') === $status ? 'checked' : '' }}>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="period-label"><i class="bi bi-cloud-sun-fill text-primary"></i> Tarde</div>
                        <div class="col-headers"><span></span><span>TUDO</span><span>PARTE</span><span>REJEITOU</span></div>
                        @foreach(['lanche' => 'Lanche', 'jantar' => 'Jantar'] as $chave => $nome)
                            <div class="meal-row"><span>{{ $nome }}</span>
                                @foreach(['tudo' => 'TUDO', 'parte' => 'PARTE', 'rejeitou' => 'REJEITOU'] as $status => $rotulo)
                                    <label class="radio {{ ($alimentacaoAtual[$chave] ?? '') === $status ? 'selected' : '' }}" title="{{ $rotulo }}">
                                        <input class="choice-input" type="radio" name="alimentacao[{{ $chave }}]" value="{{ $status }}" {{ ($alimentacaoAtual[$chave] ?? '') === $status ? 'checked' : '' }}>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="subsection-title"><span class="badge-icon blue" style="width:26px;height:26px;font-size:12px;"><i class="bi bi-droplet-fill"></i></span> LÍQUIDOS</div>
                <div class="liquids-row"><span></span><span>TUDO</span><span>PARTE</span><span>REJEITOU</span></div>
                @foreach(['leite' => 'Leite', 'suco' => 'Suco', 'agua' => 'Água'] as $chave => $nome)
                    <div class="liquid-item"><span>{{ $nome }}</span>
                        @foreach(['tudo' => 'TUDO', 'parte' => 'PARTE', 'rejeitou' => 'REJEITOU'] as $status => $rotulo)
                            <label class="radio {{ ($liquidosAtual[$chave] ?? '') === $status ? 'selected' : '' }}" title="{{ $rotulo }}">
                                <input class="choice-input" type="radio" name="liquidos[{{ $chave }}]" value="{{ $status }}" {{ ($liquidosAtual[$chave] ?? '') === $status ? 'checked' : '' }}>
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="card">
                <div class="card-title"><span class="badge-icon purple"><i class="bi bi-moon-stars-fill"></i></span> Sono</div>

                <div class="sono-question">DORMIU?</div>
                <div class="sono-btns">
                    <label class="sono-btn yes {{ optional($registro->sono)->dormiu ? 'selected' : '' }}">
                        <input type="radio" name="dormiu" value="sim" {{ optional($registro->sono)->dormiu ? 'checked' : '' }}><i class="bi bi-check-circle-fill"></i> Sim
                    </label>
                    <label class="sono-btn no {{ $registro->sono && ! $registro->sono->dormiu ? 'selected' : '' }}">
                        <input type="radio" name="dormiu" value="nao" {{ $registro->sono && ! $registro->sono->dormiu ? 'checked' : '' }}><i class="bi bi-x-circle-fill"></i> Não
                    </label>
                </div>

                <div class="period-block">
                    Período 1 das
                    <label class="time-input"><i class="bi bi-clock-fill"></i><input type="time" name="sono_inicio_1" value="{{ optional($registro->sono)->inicio_1 }}"></label>
                    às
                    <label class="time-input"><i class="bi bi-clock-fill"></i><input type="time" name="sono_fim_1" value="{{ optional($registro->sono)->fim_1 }}"></label>
                    <span class="duration-pill" id="duracao-1">--</span>
                </div>

                <div class="period-block">
                    Período 2 das
                    <label class="time-input"><i class="bi bi-clock-fill"></i><input type="time" name="sono_inicio_2" value="{{ optional($registro->sono)->inicio_2 }}"></label>
                    às
                    <label class="time-input"><i class="bi bi-clock-fill"></i><input type="time" name="sono_fim_2" value="{{ optional($registro->sono)->fim_2 }}"></label>
                    <span class="duration-pill" id="duracao-2">--</span>
                </div>
            </div>
        </div>

        <div class="card fralda-card">
            <div class="card-title"><span class="badge-icon amber"><i class="bi bi-bandaid-fill"></i></span> TROCA DE FRALDA</div>

            <div class="fralda-grid">
                <div>
                    <div class="counter-label"><i class="bi bi-droplet-fill text-primary"></i> XIXI</div>
                    <div class="counter">
                        <button type="button" onclick="changeCount('xixi',-1)"><i class="bi bi-dash-lg"></i></button>
                        <div class="count" id="xixi">{{ $xixiAtual }}</div>
                        <button type="button" onclick="changeCount('xixi',1)"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>
                <div>
                    <div class="counter-label"><i class="bi bi-exclamation-triangle-fill text-warning"></i> COCÔ</div>
                    <div class="counter">
                        <button type="button" onclick="changeCount('coco',-1)"><i class="bi bi-dash-lg"></i></button>
                        <div class="count" id="coco">{{ $cocoAtual }}</div>
                        <button type="button" onclick="changeCount('coco',1)"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>
            </div>

            <div class="obs-label">OBSERVAÇÕES</div>
            <textarea name="observacoes" placeholder="Ex: fezes com aspecto normal">{{ $observacoesAtual }}</textarea>
        </div>

        <input type="hidden" name="xixi" id="xixi-input" value="{{ $xixiAtual }}">
        <input type="hidden" name="coco" id="coco-input" value="{{ $cocoAtual }}">

        <div class="save-bar">
            <span class="text-muted small"><i class="bi bi-shield-check"></i> Dados deste registro</span>
            <button class="save-button" type="submit"><i class="bi bi-save2-fill me-1"></i> Atualizar registro</button>
        </div>
    </form>

    @include('registros._script-form')
@endsection