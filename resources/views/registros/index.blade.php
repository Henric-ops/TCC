@extends('layout.app')

@section('title', 'Registros Diários')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
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

    <div class="registros-index-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="freq-title mb-0">Registros Diários</h1>
        <a href="{{ route('registros.selecionar-turma') }}" class="usuario-button usuario-button-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Novo registro
        </a>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success">{{ session('sucesso') }}</div>
    @endif

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
                <option value="">Todos</option>
                @foreach($alunosParaFiltro as $aluno)
                    <option value="{{ $aluno->id }}" {{ (string) $alunoId === (string) $aluno->id ? 'selected' : '' }}>
                        {{ $aluno->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="freq-filtro-item">
            <label>De</label>
            <input type="date" name="inicio" value="{{ request('inicio') }}">
        </div>
        <div class="freq-filtro-item">
            <label>Até</label>
            <input type="date" name="fim" value="{{ request('fim') }}">
        </div>

        <button type="submit" class="usuario-button usuario-button-primary">
            <i class="bi bi-search" aria-hidden="true"></i>
            Buscar
        </button>

        <a href="{{ route('registros.index') }}" class="usuario-button usuario-button-muted">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
            Limpar
        </a>
    </form>

    <p class="text-muted small mb-3">
        {{ $temFiltroData ? 'Mostrando período selecionado.' : 'Mostrando apenas hoje — use os filtros para ver outras datas.' }}
    </p>

    <div class="freq-panel">
        @if($registros->isEmpty())
            <div class="freq-empty">Nenhum registro encontrado.</div>
        @else
            <table class="freq-table freq-history-table registros-history-table">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Data</th>
                        <th>Professor</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros as $registro)
                        <tr>
                            <td data-label="Aluno">{{ $registro->aluno->nome }}</td>
                            <td data-label="Data">{{ $registro->data->format('d/m/Y') }}</td>
                            <td data-label="Professor">{{ $registro->professor->nome }}</td>
                            <td class="text-end" data-label="Ações">
                                @if(auth()->user()->perfil === 'admin' || $registro->professor_id === auth()->id())
                                    <div class="registros-row-actions">
                                        <a href="{{ route('registros.edit', $registro) }}" class="usuario-action usuario-action-primary"
                                            title="Editar registro" aria-label="Editar registro">
                                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                            <span>Editar</span>
                                        </a>
                                        <form action="{{ route('registros.destroy', $registro) }}" method="POST"
                                            onsubmit="return confirm('Remover este registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="usuario-action usuario-action-danger" title="Excluir registro"
                                                aria-label="Excluir registro">
                                                <i class="bi bi-trash3" aria-hidden="true"></i>
                                                <span>Excluir</span>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">Sem permissão</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-3">{{ $registros->appends(request()->query())->links() }}</div>
@endsection