@extends('layout.app')

@section('title', 'Frequência')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
@endpush

@section('content')
    <h1 class="freq-title">Frequência</h1>

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
        @if($frequencias->isEmpty())
            <div class="freq-empty">Nenhum registro encontrado.</div>
        @else
            <table class="freq-table">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Turma</th>
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($frequencias as $freq)
                        <tr>
                            <td>{{ $freq->aluno->nome }}</td>
                            <td>{{ $freq->turma->nome }}</td>
                            <td>{{ $freq->data->format('d/m/Y') }}</td>
                            <td>
                                @if($freq->presente)
                                    <span class="status-pill ok">Presente</span>
                                @else
                                    <span class="status-pill falta">Falta</span>
                                    @if($freq->justificativa)
                                        <span class="freq-justificativa">{{ $freq->justificativa }}</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-3">{{ $frequencias->appends(request()->query())->links() }}</div>
@endsection