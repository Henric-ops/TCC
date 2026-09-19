@extends('layout.app')

@section('title', 'Histórico de frequência')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
@endpush

@section('content')
    <h1 class="freq-title">Histórico de frequência</h1>

    <form method="GET" class="freq-filtros">
        <div class="freq-filtro-item">
            <label>Turma</label>
            <select name="turma_id" onchange="this.form.submit()">
                <option value="">Todas</option>
                @foreach($turmasPermitidas as $turma)
                    <option value="{{ $turma->id }}" {{ (string) $turmaId === (string) $turma->id ? 'selected' : '' }}>
                        {{ $turma->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="freq-filtro-item">
            <label>Aluno</label>
            <select name="aluno_id" onchange="this.form.submit()">
                <option value="">Todos (ver por dia)</option>
                @foreach($alunos as $aluno)
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
                <input type="date" name="data" value="{{ $data }}" onchange="this.form.submit()">
            </div>
        @endif
    </form>

    @if($alunoId)
        {{-- Histórico do aluno selecionado --}}
        <div class="freq-panel">
            @if($registros->isEmpty())
                <div class="freq-empty">Nenhum registro nesse período.</div>
            @else
                <table class="freq-table">
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
                                <td>{{ $registro->turma->nome }}</td>
                                <td>{{ $registro->data->format('d/m/Y') }}</td>
                                <td>
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
        {{-- Visão do dia, por turma --}}
        <div class="freq-panel">
            @if($resumo->isEmpty())
                <div class="freq-empty">Nenhum registro nesse dia.</div>
            @else
                <table class="freq-table">
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
                                <td>{{ $dia->turma->nome }}</td>
                                <td><span class="status-pill ok">{{ $dia->presentes }}</span></td>
                                <td><span class="status-pill falta">{{ $dia->total - $dia->presentes }}</span></td>
                                <td>
                                    @if($naoMarcados > 0)
                                        <span class="status-pill pendente">{{ $naoMarcados }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
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