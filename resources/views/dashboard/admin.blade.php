@extends('layout.app')

@section('title', 'Dashboard - Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="dash-top">
        <div>
            <div class="dash-greeting">Olá, {{ $user->nome }} 👋</div>
            <p class="dash-date">{{ now()->locale('pt_BR')->translatedFormat('l, d \d\e F') }}</p>
        </div>
    </div>

    {{-- Resumo do dia --}}
    <div class="hoje-bar">
        <div class="hoje-item">
            <span class="hoje-valor">{{ $turmasComFrequenciaHoje }}/{{ $totalTurmas }}</span>
            <span class="hoje-label">turmas com frequência batida hoje</span>
        </div>
        <div class="hoje-divider"></div>
        <div class="hoje-item">
            <span class="hoje-valor">{{ $registrosHoje }}</span>
            <span class="hoje-label">registros diários feitos hoje</span>
        </div>
        <div class="hoje-divider"></div>
        <div class="hoje-item">
            <span class="hoje-valor {{ $totalPendentes > 0 ? 'text-warning' : '' }}">{{ $totalPendentes }}</span>
            <span class="hoje-label">cadastros aguardando aprovação</span>
        </div>
    </div>

    <div class="dash-columns">
        {{-- Coluna esquerda: pendências + turmas --}}
        <div class="dash-col-main">
            @if($pendentes->isNotEmpty())
                <div class="dash-panel mb-4">
                    <div class="dash-panel-header">
                        <h2>Aguardando aprovação</h2>
                        <a href="{{ route('admin.usuarios.index') }}">Ver todos ({{ $totalPendentes }})</a>
                    </div>
                    <div class="pendencia-list">
                        @foreach($pendentes as $pendente)
                            <div class="pendencia-item">
                                <div>
                                    <strong>{{ $pendente->nome }}</strong>
                                    <span class="pendencia-perfil">{{ ucfirst($pendente->perfil) }}</span>
                                </div>
                                <a href="{{ route('admin.usuarios.verificar', $pendente) }}" class="btn-verificar">Verificar</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="dash-panel">
                <div class="dash-panel-header">
                    <h2>Turmas</h2>
                    <a href="{{ route('admin.turmas.index') }}">Ver todas</a>
                </div>

                @if($turmas->isEmpty())
                    <div class="dash-empty">Nenhuma turma cadastrada ainda.</div>
                @else
                    <div class="turma-list">
                        @foreach($turmas as $turma)
                            <div class="turma-item">
                                <div class="turma-info">
                                    <strong>{{ $turma->nome }}</strong>
                                    <span
                                        class="periodo-pill {{ \Illuminate\Support\Str::slug($turma->periodo, '') }}">{{ $turma->periodo }}</span>
                                </div>
                                <span class="turma-alunos">{{ $turma->alunos_count }}
                                    {{ $turma->alunos_count === 1 ? 'aluno' : 'alunos' }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>


        <div class="dash-panel">
            <div class="dash-panel-header">
                <h2>Números gerais</h2>
            </div>
            <div class="numeros-lista">
                <div class="numero-item">
                    <span>Alunos</span>
                    <strong>{{ $totalAlunos }}</strong>
                </div>
                <div class="numero-item">
                    <span>Professores</span>
                    <strong>{{ $totalProfessores }}</strong>
                </div>
                <div class="numero-item">
                    <span>Responsáveis</span>
                    <strong>{{ $totalResponsaveis }}</strong>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection