@extends('layout.app')

@section('title', $mensagem->assunto)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="card p-4" style="max-width: 700px;">
        <h1 class="h4 mb-1">{{ $mensagem->assunto }}</h1>
        <p class="text-muted small mb-4">
            De {{ $mensagem->remetente->nome }} · {{ $mensagem->enviado_em->format('d/m/Y \à\s H:i') }}
            @if($mensagem->aluno)
                · Sobre {{ $mensagem->aluno->nome }}
            @endif
        </p>

        <div class="comunicado-content">{{ $mensagem->conteudo }}</div>

        <p> </p>

        <div class="comunicado-actions">
            <a href="{{ route('dashboard') }}" class="usuario-button usuario-button-muted">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Voltar
            </a>
        </div>
    </div>
@endsection