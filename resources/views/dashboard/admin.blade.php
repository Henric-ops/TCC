@extends('layout.app')

@section('title', 'Dashboard - Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="dash-greeting">Olá, {{ $user->nome }} 👋</div>
    <p class="dash-subtitle">Aqui está um resumo do que está acontecendo na escola.</p>

    @if($pendentes > 0)
        <div class="dash-alert">
            <div class="dash-alert-text">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>Você tem <strong>{{ $pendentes }}</strong>
                    {{ $pendentes === 1 ? 'cadastro pendente' : 'cadastros pendentes' }} de aprovação.</span>
            </div>
            <a href="{{ route('admin.usuarios.index') }}" class="btn">Revisar agora</a>
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
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ano</th>
                        <th>Período</th>
                        <th>Alunos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($turmas as $turma)
                        <tr>
                            <td>{{ $turma->nome }}</td>
                            <td>{{ $turma->ano }}</td>
                            <td>
                                <span
                                    class="periodo-pill {{ \Illuminate\Support\Str::slug($turma->periodo, '') }}">{{ $turma->periodo }}</span>
                            </td>
                            <td>{{ $turma->alunos_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection