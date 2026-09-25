@extends('layout.app')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alunos.css') }}">
@endpush

@section('content')
    <div class="dash-greeting">Olá, {{ $user->nome }} 👋</div>
    <p class="dash-subtitle">Veja como seus filhos estão hoje.</p>

    @if($filhos->isEmpty())
        <div class="dash-panel">
            <div class="dash-empty">Nenhum aluno vinculado à sua conta ainda. Fale com a escola se isso não estiver certo.</div>
        </div>
    @else
        <div class="filho-grid">
            @foreach($filhos as $filho)
                <div class="filho-card">
                    <div class="filho-header">
                        @if($filho->foto)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($filho->foto) }}" alt="{{ $filho->nome }}"
                                class="avatar-photo">
                        @else
                            <div class="avatar-placeholder"><i class="bi bi-person"></i></div>
                        @endif
                        <div>
                            <div class="filho-nome">{{ $filho->nome }}</div>
                            <div class="filho-meta">{{ $filho->data_nascimento->age }} anos ·
                                {{ $filho->turmas->pluck('nome')->join(', ') ?: 'Sem turma' }}
                            </div>
                        </div>
                    </div>

                    <div class="filho-status-row">
                        @if(!$filho->frequenciaHoje)
                            <span class="status-pill pendente">Frequência pendente</span>
                        @elseif($filho->frequenciaHoje->presente)
                            <span class="status-pill ok">Presente hoje</span>
                        @else
                            <span class="status-pill falta">Falta hoje</span>
                        @endif

                        @if($filho->registroHoje)
                            <span class="status-pill ok">Registro do dia feito</span>
                        @else
                            <span class="status-pill falta">Sem registro hoje</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="dash-panel">
            <div class="dash-panel-header">
                <h2>Comunicados recentes</h2>
            </div>

            @if($comunicadosRecentes->isEmpty())
                <div class="dash-empty">Nenhum comunicado recebido ainda.</div>
            @else
                <div class="feed">
                    @foreach($comunicadosRecentes as $comunicado)
                        <a href="{{ route('comunicados.comunicados-responsavel', $comunicado) }}" class="feed-item feed-item-link">
                            <span class="feed-dot"></span>
                            <div>
                                <p class="feed-text">
                                    <strong>{{ $comunicado->assunto }}</strong> — de {{ $comunicado->remetente->nome }}
                                </p>
                                <span class="feed-time">{{ $comunicado->enviado_em->diffForHumans() }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
@endsection