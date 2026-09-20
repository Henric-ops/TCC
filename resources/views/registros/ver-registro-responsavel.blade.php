@extends('layout.app')

@section('title', 'Registros diários')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
@endpush

@section('content')
    <h1 class="freq-title">Registros diários</h1>

    <form method="GET" class="freq-filtros">
        @if($filhos->count() > 1)
            <div class="freq-filtro-item">
                <label>Filho</label>
                <select name="aluno_id" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach($filhos as $filho)
                        <option value="{{ $filho->id }}" {{ request('aluno_id') == $filho->id ? 'selected' : '' }}>{{ $filho->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="freq-filtro-item">
            <label>De</label>
            <input type="date" name="inicio" value="{{ request('inicio') }}" onchange="this.form.submit()">
        </div>
        <div class="freq-filtro-item">
            <label>Até</label>
            <input type="date" name="fim" value="{{ request('fim') }}" onchange="this.form.submit()">
        </div>
    </form>

    <div class="freq-panel">
        @if($registros->isEmpty())
            <div class="freq-empty">Nenhum registro ainda.</div>
        @else
            <table class="freq-table">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Data</th>
                        <th>Professor</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registros as $registro)
                        <tr>
                            <td>{{ $registro->aluno->nome }}</td>
                            <td>{{ $registro->data->format('d/m/Y') }}</td>
                            <td>{{ $registro->professor->nome }}</td>
                            <td class="text-end">
                                <a href="{{ route('registros.meu-detalhe', $registro) }}" class="btn-ver">Ver detalhes</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-3">{{ $registros->appends(request()->query())->links() }}</div>
@endsection