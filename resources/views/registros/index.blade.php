@extends('layout.app')

@section('title', 'Registros Diários')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="freq-title mb-0">Registros Diários</h1>
        <a href="{{ route('registros.selecionar-turma') }}" class="freq-btn freq-btn-primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Novo registro
        </a>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success">{{ session('sucesso') }}</div>
    @endif

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
            <input type="date" name="inicio" value="{{ request('inicio') }}" onchange="this.form.submit()">
        </div>
        <div class="freq-filtro-item">
            <label>Até</label>
            <input type="date" name="fim" value="{{ request('fim') }}" onchange="this.form.submit()">
        </div>
    </form>

    <p class="text-muted small mb-3">
        {{ $temFiltroData ? 'Mostrando período selecionado.' : 'Mostrando apenas hoje — use os filtros para ver outras datas.' }}
    </p>

    <div class="freq-panel">
        @if($registros->isEmpty())
            <div class="freq-empty">Nenhum registro encontrado.</div>
        @else
            <table class="freq-table">
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
                            <td>{{ $registro->aluno->nome }}</td>
                            <td>{{ $registro->data->format('d/m/Y') }}</td>
                            <td>{{ $registro->professor->nome }}</td>
                            <td class="text-end">
                                <div class="freq-actions">
                                    <a href="{{ route('registros.edit', $registro) }}" class="freq-btn freq-btn-edit">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i> Editar
                                    </a>
                                    <form action="{{ route('registros.destroy', $registro) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Remover este registro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="freq-btn freq-btn-delete">
                                            <i class="bi bi-trash3" aria-hidden="true"></i> Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-3">{{ $registros->appends(request()->query())->links() }}</div>
@endsection