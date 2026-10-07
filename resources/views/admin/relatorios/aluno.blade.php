@extends('layout.app')

@section('title', 'Relatório - ' . $aluno->nome)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">
        <h1 class="h4 mb-0">{{ $aluno->nome }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.relatorios.aluno.pdf', request()->query()) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-download"></i> Baixar PDF
            </a>
            <a href="{{ route('admin.relatorios.index') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
        </div>
    </div>
    <p class="text-muted small mb-4">Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} a
        {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
    </p>

    @include('admin.relatorios._conteudo', ['dados' => $dados])
@endsection