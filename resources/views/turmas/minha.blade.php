@extends('layout.app')

@section('title', $turma->nome)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/turmas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
<div class="turmas-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>{{ $turma->nome }}</h1>

    </div>

    <div class="d-flex align-items-center gap-3">
        <span class="turmas-total">

        </span>
        <a href="{{ route('turmas.minhas') }}" class="usuario-button usuario-button-muted">Voltar</a>
    </div>
</div>

<div class="card turmas-table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Idade</th>
                    <th>Frequência</th>
                    <th>Registros</th>
                    <th class="text-end">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse($turma->alunos as $aluno)
                <tr>
                    @php($registroHoje = $aluno->registrosDiarios->first())
                    @php($frequenciaHoje = $frequenciasHoje->get($aluno->id))
                    <td><strong>{{ $aluno->nome }}</strong></td>
                    <td>{{ $aluno->data_nascimento->age }} anos</td>
                    <td>
                        @if(!$frequenciaHoje)
                            <span class="situacao-frequencia status-nao-informado">Não informado</span>
                        @elseif($frequenciaHoje->presente)
                            <span class="situacao-frequencia status-presente">Presente</span>
                        @else
                            <span class="situacao-frequencia status-ausente">Ausente</span>
                        @endif
                    </td>
                    <td>
                        @if($registroHoje)
                            <span class="registro-hoje-status registrado">Registrado</span>
                        @else
                            <span class="registro-hoje-status pendente">Pendente</span>
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        @if(!$registroHoje)
                            <a href="{{ route('registros.create', ['aluno_id' => $aluno->id]) }}"
                                class="usuario-button usuario-button-primary" title="Fazer registro"
                                aria-label="Fazer registro para {{ $aluno->nome }}">
                                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                            </a>
                        @elseif($registroHoje->professor_id === auth()->id())
                            <a href="{{ route('registros.edit', $registroHoje) }}"
                                class="usuario-button usuario-button-muted" title="Visualizar ou editar registro"
                                aria-label="Visualizar ou editar registro de {{ $aluno->nome }}">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </a>
                        @else
                            <span class="text-muted small">Sem permissão para editar</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="turma-empty">
                            <i class="bi bi-person-x" aria-hidden="true"></i>
                            <span>Nenhum aluno vinculado a essa turma.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection