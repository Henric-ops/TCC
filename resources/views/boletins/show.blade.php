@extends('layout.app')

@section('title', 'Visualizar boletim')

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

                    <h1 class="h4 mb-1">
                        Visualizar boletim
                    </h1>

                    <p class="usuario-form-subtitle">
                        Consulte as informações e a avaliação do aluno.
                    </p>

                </div>

            </div>

            <a href="{{ route('boletins.index') }}" class="usuario-button usuario-button-muted">

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

                    <div class="col-md-4 mb-3 usuario-form-field">

                        <label class="form-label">
                            Aluno
                        </label>

                        <div class="boletim-detail-value">
                            {{ $boletim->aluno->nome }}
                        </div>

                    </div>


                    <div class="col-md-4 mb-3 usuario-form-field">

                        <label class="form-label">
                            Escola
                        </label>

                        <div class="boletim-detail-value">
                            {{ $boletim->aluno->escola->nome ?? 'Não informado' }}
                        </div>

                    </div>


                    <div class="col-md-4 mb-3 usuario-form-field">

                        <label class="form-label">
                            Turma
                        </label>

                        <div class="boletim-detail-value">

                            {{ $boletim->aluno->turmas->first()->nome ?? 'Não informado' }}

                        </div>

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3 usuario-form-field">

                        <label class="form-label">
                            Período
                        </label>

                        <div class="boletim-detail-value">
                            {{ $boletim->periodo }}
                        </div>

                    </div>


                    <div class="col-md-6 mb-3 usuario-form-field">

                        <label class="form-label">
                            Data do registro
                        </label>

                        <div class="boletim-detail-value">
                            {{ $boletim->created_at->format('d/m/Y H:i') }}
                        </div>

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

                            <a href="{{ route('boletins.pdf', $boletim) }}" class="usuario-button usuario-button-primary">

                                <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>

                                Baixar avaliação em PDF

                            </a>

                        </div>

                    </div>

                @endif



                @if ($boletim->documento || $boletim->arquivo_pdf)

                    <div class="mb-4">

                        <h2 class="usuario-form-section mb-3">

                            <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>

                            Documento do boletim

                        </h2>


                        <div class="boletim-pdf-panel">

                            <div>

                                <strong>
                                    Boletim em PDF
                                </strong>

                                <small class="d-block text-muted">
                                    Documento anexado ao boletim.
                                </small>

                            </div>


                            <div class="d-flex flex-wrap gap-2">

                                @if ($boletim->documento)
                                    <a href="{{ route('boletins.documento', $boletim) }}" target="_blank" rel="noopener"
                                        class="usuario-button usuario-button-primary">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        Visualizar PDF
                                    </a>
                                    <a href="{{ route('boletins.documento', ['boletim' => $boletim, 'download' => 1]) }}"
                                        class="usuario-button usuario-button-primary">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        Baixar PDF
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $boletim->arquivo_pdf) }}" target="_blank" rel="noopener"
                                        class="usuario-button usuario-button-primary">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        Visualizar PDF
                                    </a>
                                    <a href="{{ asset('storage/' . $boletim->arquivo_pdf) }}" download
                                        class="usuario-button usuario-button-primary">
                                        <i class="bi bi-download" aria-hidden="true"></i>
                                        Baixar PDF
                                    </a>
                                @endif

                            </div>

                        </div>

                    </div>

                @endif



                @if (!$boletim->observacao && !$boletim->documento && !$boletim->arquivo_pdf)

                    <div class="alert alert-warning">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        Este boletim não possui uma avaliação escrita nem
                        um documento PDF anexado.

                    </div>

                @endif



                <div class="boletim-meta">

                    <div class="row">

                        <div class="col-md-6 mb-3 mb-md-0">

                            <small class="boletim-meta-label">
                                Registrado por
                            </small>

                            <div class="boletim-meta-value">
                                {{ $boletim->usuario->nome ?? 'Não informado' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <small class="boletim-meta-label">
                                Data do registro
                            </small>

                            <div class="boletim-meta-value">
                                {{ $boletim->created_at->format('d/m/Y H:i') }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection