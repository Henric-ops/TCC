@extends('layout.app')

@section('title', 'Relatório')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/relatorio-responsavel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <main class="responsavel-report">
        <header class="responsavel-report__header">
            <div>
                <h1>Relatório</h1>
            </div>
            <a href="{{ route('relatorio.meu.pdf', request()->query()) }}"
                class="responsavel-report__download usuario-button usuario-button-primary">
                <i class="bi bi-download" aria-hidden="true"></i>
                <span>Baixar relatório em PDF</span>
            </a>
        </header>

        <form method="GET" class="responsavel-report__filters">
            @if($filhos->count() > 1)
                <div class="responsavel-report__field responsavel-report__field--student">
                    <label for="aluno_id">Aluno</label>
                    <select id="aluno_id" name="aluno_id" class="form-select" onchange="this.form.submit()">
                        @foreach($filhos as $filho)
                            <option value="{{ $filho->id }}" {{ $filho->id === $aluno->id ? 'selected' : '' }}>
                                {{ $filho->nome }}{{ $filho->trashed() ? ' (desligado)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="aluno_id" value="{{ $aluno->id }}">
            @endif

            <div class="responsavel-report__field">
                <label for="inicio">De</label>
                <input id="inicio" type="date" name="inicio" value="{{ $inicio }}" class="form-control"
                    onchange="this.form.submit()">
            </div>
            <div class="responsavel-report__field">
                <label for="fim">Até</label>
                <input id="fim" type="date" name="fim" value="{{ $fim }}" class="form-control"
                    onchange="this.form.submit()">
            </div>
        </form>
    </main>
@endsection