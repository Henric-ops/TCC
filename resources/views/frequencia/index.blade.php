@extends('layout.app')

@section('title', 'Histórico de frequência')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
@endpush

@section('content')
    @php
        $todosTurmas = $turmasTotais->map(fn($turma) => ['id' => $turma->id, 'nome' => $turma->nome, 'escola_id' => $turma->escola_id])->values()->all();
    @endphp

    <script>
        window.frequenciaFiltrosData = {
            turmasPorEscola: @json($turmasPorEscola ?? []),
            alunosPorTurma: @json($alunosPorTurma ?? []),
            todasTurmas: @json($todosTurmas ?? []),
            turmaAtual: '{{ (string) $turmaId ?: '' }}',
            alunoAtual: '{{ (string) $alunoId ?: '' }}'
        };
    </script>
    <script src="{{ asset('js/frequencia-filtros.js') }}"></script>

    <h1 class="freq-title">Histórico de frequência</h1>

    <form method="GET" class="freq-filtros">
        <div class="freq-filtro-item">
            <label for="escola_id">Escola</label>
            <select name="escola_id" id="escola_id">
                <option value="">Todas as escolas</option>
                @foreach($escolasPermitidas as $escola)
                    <option value="{{ $escola->id }}" {{ (string) $escolaId === (string) $escola->id ? 'selected' : '' }}>
                        {{ $escola->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="freq-filtro-item">
            <label for="turma_id">Turma</label>
            <select name="turma_id" id="turma_id">
                <option value="">Todas</option>
                @foreach($turmasPermitidas as $turma)
                    <option value="{{ $turma->id }}" {{ (string) $turmaId === (string) $turma->id ? 'selected' : '' }}>
                        {{ $turma->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="freq-filtro-item">
            <label for="aluno_id">Aluno</label>
            <select name="aluno_id" id="aluno_id">
                <option value="">Todos (ver por dia)</option>
                @foreach($alunosParaFiltro as $aluno)
                    <option value="{{ $aluno->id }}" {{ (string) $alunoId === (string) $aluno->id ? 'selected' : '' }}>
                        {{ $aluno->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        @if($alunoId)
            <div class="freq-filtro-item">
                <label>De</label>
                <input type="date" name="inicio" value="{{ request('inicio') }}" onchange="this.form.submit()">
            </div>
            <div class="freq-filtro-item">
                <label>Até</label>
                <input type="date" name="fim" value="{{ request('fim') }}" onchange="this.form.submit()">
            </div>
        @else
            <div class="freq-filtro-item">
                <label>Data</label>
                <input type="date" name="data" value="{{ $data ?? now()->format('Y-m-d') }}" onchange="this.form.submit()">
            </div>
        @endif



        <button type="submit" class="usuario-button usuario-button-primary">
            <i class="bi bi-search" aria-hidden="true"></i>
            Buscar
        </button>

        <a href="{{ route('frequencia.index') }}" class="usuario-button usuario-button-muted">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
            Limpar
        </a>
    </form>

    @if($alunoId)
        <div class="freq-panel">
            @if($registros->isEmpty())
                <div class="freq-empty">Nenhum registro nesse período.</div>
            @else
                <table class="freq-table freq-history-table">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registros as $registro)
                            <tr>
                                <td data-label="Turma">{{ $registro->turma->nome }}</td>
                                <td data-label="Data">{{ $registro->data->format('d/m/Y') }}</td>
                                <td data-label="Status">
                                    @if($registro->presente)
                                        <span class="status-pill ok">Presente</span>
                                    @else
                                        <span class="status-pill falta">Falta</span>
                                        @if($registro->justificativa)
                                            <span class="freq-justificativa">{{ $registro->justificativa }}</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="mt-3">{{ $registros->links() }}</div>
    @else
        <div class="freq-panel">
            @if($resumo->isEmpty())
                <div class="freq-empty">Nenhum registro nesse dia.</div>
            @else
                <table class="freq-table freq-history-table">
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Presentes</th>
                            <th>Faltas</th>
                            <th>Não marcados</th>
                            <th class="text-end">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resumo as $dia)
                            @php $naoMarcados = $dia->turma->alunos_count - $dia->total; @endphp
                            <tr>
                                <td data-label="Turma">{{ $dia->turma->nome }}</td>
                                <td data-label="Presentes"><span class="status-pill ok">{{ $dia->presentes }}</span></td>
                                <td data-label="Faltas"><span class="status-pill falta">{{ $dia->total - $dia->presentes }}</span></td>
                                <td data-label="Não marcados">
                                    @if($naoMarcados > 0)
                                        <span class="status-pill pendente">{{ $naoMarcados }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end" data-label="Ação">
                                    <a href="{{ route('frequencia.form', ['turma_id' => $dia->turma_id, 'data' => $dia->data]) }}"
                                        class="btn-ver">
                                        Ver / editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
@endsection