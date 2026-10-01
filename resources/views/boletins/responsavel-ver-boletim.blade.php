@extends('layout.app')

@section('title', 'Boletim')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/boletins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')

    <div class="usuario-form-page">
        <div class="usuario-form-header">
            <div class="usuario-form-title">
                <span class="usuario-form-title-icon" aria-hidden="true">
                    <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                </span>
                <div>
                    <h1 class="h4 mb-1">Boletim</h1>
                    <p class="usuario-form-subtitle">Informações e avaliação do aluno.</p>
                </div>
            </div>

            <a href="{{ route('boletins.meus') }}" class="usuario-button usuario-button-muted">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Voltar
            </a>
        </div>

        <div class="card usuario-form-card">
            <div class="card-header">
                <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                <strong>Dados do boletim</strong>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3 usuario-form-field">
                        <label class="form-label">Aluno</label>
                        <div class="boletim-detail-value">{{ $boletim->aluno->nome }}</div>
                    </div>

                    <div class="col-md-6 mb-3 usuario-form-field">
                        <label class="form-label">Período</label>
                        <div class="boletim-detail-value">{{ $boletim->periodo }}</div>
                    </div>
                </div>

                <hr>

                @if ($boletim->observacao)
                    <div class="mb-4">
                        <h2 class="usuario-form-section mb-3">
                            <i class="bi bi-chat-left-text" aria-hidden="true"></i>
                            Avaliação
                        </h2>
                        <div class="boletim-detail-text">
                            {!! nl2br(e($boletim->observacao)) !!}
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('boletins.meu-pdf', $boletim) }}"
                                class="usuario-button usuario-button-primary">
                                <i class="bi bi-download" aria-hidden="true"></i>
                                Baixar avaliação
                            </a>
                        </div>
                    </div>
                @endif

                @if ($boletim->arquivo_pdf)
                    <div class="mb-4">
                        <h2 class="usuario-form-section mb-3">
                            <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                            Documento do boletim
                        </h2>
                        <div class="boletim-pdf-panel">
                            <div>
                                <strong>Boletim em PDF</strong>
                                <small class="d-block text-muted">Documento disponibilizado pela escola.</small>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ asset('storage/' . $boletim->arquivo_pdf) }}" target="_blank"
                                    class="usuario-button usuario-button-primary">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                    Visualizar PDF
                                </a>
                                <a href="{{ asset('storage/' . $boletim->arquivo_pdf) }}" download
                                    class="usuario-button usuario-button-primary">
                                    <i class="bi bi-download" aria-hidden="true"></i>
                                    Baixar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection