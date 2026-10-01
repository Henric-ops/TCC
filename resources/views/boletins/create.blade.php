@extends('layout.app')

@section('title', 'Novo boletim')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')

    <div class="usuario-form-page">
        <div class="usuario-form-header">
            <div class="usuario-form-title">
                <span class="usuario-form-title-icon" aria-hidden="true">
                    <i class="bi bi-file-earmark-plus" aria-hidden="true"></i>
                </span>
                <div>
                    <h1 class="h4 mb-1">Novo boletim</h1>
                    <p class="usuario-form-subtitle">Registre a avaliação do aluno ou envie o boletim em PDF.</p>
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
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Não foi possível cadastrar o boletim.</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('boletins.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="aluno_id" class="form-label">Aluno</label>

                            <select name="aluno_id" id="aluno_id"
                                class="form-select @error('aluno_id') is-invalid @enderror" required>
                                <option value="">Selecione um aluno</option>
                                @foreach ($alunos as $aluno)
                                    <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                                        {{ $aluno->nome }}
                                    </option>
                                @endforeach
                            </select>

                            @error('aluno_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="periodo" class="form-label">Período</label>

                            <select name="periodo" id="periodo" class="form-select @error('periodo') is-invalid @enderror"
                                required>
                                <option value="">Selecione</option>

                                <option value="1º Semestre de 2026" {{ old('periodo') == '1º Semestre de 2026' ? 'selected' : '' }}>
                                    1º Semestre de 2026
                                </option>

                                <option value="2º Semestre de 2026" {{ old('periodo') == '2º Semestre de 2026' ? 'selected' : '' }}>
                                    2º Semestre de 2026
                                </option>
                            </select>

                            @error('periodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 usuario-form-field">
                        <label for="observacao" class="form-label">Observação / Avaliação</label>

                        <textarea name="observacao" id="observacao" rows="8"
                            class="form-control @error('observacao') is-invalid @enderror"
                            placeholder="Escreva aqui a avaliação ou observações sobre o aluno...">{{ old('observacao') }}</textarea>

                        <small class="usuario-form-help">Campo opcional. Você pode escrever a avaliação diretamente no
                            sistema.</small>

                        @error('observacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 usuario-form-field">
                        <label for="arquivo_pdf" class="form-label">Documento do boletim</label>

                        <input type="file" name="arquivo_pdf" id="arquivo_pdf"
                            class="form-control @error('arquivo_pdf') is-invalid @enderror" accept=".pdf,application/pdf">

                        <small class="usuario-form-help">Opcional. Envie um arquivo PDF caso o boletim já esteja pronto.
                            Tamanho máximo: 10 MB.</small>

                        @error('arquivo_pdf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="usuario-form-actions">
                        <a href="{{ route('boletins.index') }}" class="usuario-button usuario-button-muted">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="usuario-button usuario-button-primary">
                            <i class="bi bi-file-earmark-plus" aria-hidden="true"></i>
                            Salvar boletim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection