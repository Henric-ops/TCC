@extends('layout.app')

@section('title', 'Editar boletim')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    @php
        $tipoPeriodoSelecionado = old('tipo_periodo', $boletim->tipo_periodo);
        $numeroPeriodoSelecionado = old('numero_periodo', $boletim->numero_periodo);
        $quantidadePeriodos = [
            'bimestre' => 4,
            'trimestre' => 3,
            'semestre' => 2,
        ][$tipoPeriodoSelecionado] ?? 0;
        $temDocumento = $boletim->documento || $boletim->arquivo_pdf;
    @endphp

    <div class="usuario-form-page">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h4 mb-1">Editar boletim</h1>
                <p class="usuario-form-subtitle">Atualize as informações, a avaliação ou o PDF anexado.</p>
            </div>

            <a href="{{ route('boletins.show', $boletim) }}" class="usuario-button usuario-button-muted ms-auto">
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
                            <strong>Não foi possível atualizar o boletim.</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('boletins.update', $boletim) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="aluno_id" class="form-label">Aluno</label>
                            <select name="aluno_id" id="aluno_id"
                                class="form-select @error('aluno_id') is-invalid @enderror" required>
                                @foreach ($alunos as $aluno)
                                    <option value="{{ $aluno->id }}"
                                        @selected((string) old('aluno_id', $boletim->aluno_id) === (string) $aluno->id)>
                                        {{ $aluno->nome }}{{ $aluno->trashed() ? ' (desligado)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('aluno_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="ano" class="form-label">Ano</label>
                            <input type="text" id="ano" class="form-control" value="{{ $boletim->ano }}" readonly>
                            <small class="usuario-form-help">O ano do boletim não pode ser alterado.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="tipo_periodo" class="form-label">Regime letivo</label>
                            <select name="tipo_periodo" id="tipo_periodo"
                                class="form-select @error('tipo_periodo') is-invalid @enderror" required>
                                <option value="bimestre" @selected($tipoPeriodoSelecionado === 'bimestre')>Bimestre</option>
                                <option value="trimestre" @selected($tipoPeriodoSelecionado === 'trimestre')>Trimestre</option>
                                <option value="semestre" @selected($tipoPeriodoSelecionado === 'semestre')>Semestre</option>
                            </select>
                            @error('tipo_periodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="numero_periodo" class="form-label">Período</label>
                            <select name="numero_periodo" id="numero_periodo"
                                class="form-select @error('numero_periodo') is-invalid @enderror" required>
                                @for ($periodo = 1; $periodo <= $quantidadePeriodos; $periodo++)
                                    <option value="{{ $periodo }}"
                                        @selected((string) $numeroPeriodoSelecionado === (string) $periodo)>
                                        {{ $periodo }}º {{ ucfirst($tipoPeriodoSelecionado) }}
                                    </option>
                                @endfor
                            </select>
                            @error('numero_periodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 usuario-form-field">
                        <label for="observacao" class="form-label">Avaliação</label>
                        <textarea name="observacao" id="observacao" rows="8"
                            class="form-control @error('observacao') is-invalid @enderror"
                            placeholder="Escreva aqui a avaliação ou observações sobre o aluno...">{{ old('observacao', $boletim->observacao) }}</textarea>
                        @error('observacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 usuario-form-field">
                        <label for="arquivo_pdf" class="form-label">Documento do boletim</label>

                        @if ($temDocumento)
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="usuario-form-help">Já existe um PDF anexado.</span>
                                @if ($boletim->documento)
                                    <a href="{{ route('boletins.documento', $boletim) }}" target="_blank" rel="noopener"
                                        class="usuario-button usuario-button-muted">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        Visualizar PDF atual
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $boletim->arquivo_pdf) }}" target="_blank" rel="noopener"
                                        class="usuario-button usuario-button-muted">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                        Visualizar PDF atual
                                    </a>
                                @endif
                            </div>

                            <div class="form-check mb-2">
                                <input type="checkbox" name="remover_arquivo_pdf" id="remover_arquivo_pdf" value="1"
                                    class="form-check-input" @checked(old('remover_arquivo_pdf'))>
                                <label for="remover_arquivo_pdf" class="form-check-label">
                                    Remover o PDF atual
                                </label>
                            </div>
                        @endif

                        <input type="file" name="arquivo_pdf" id="arquivo_pdf"
                            class="form-control @error('arquivo_pdf') is-invalid @enderror" accept=".pdf,application/pdf">
                        <small class="usuario-form-help">
                            Opcional. Envie um novo PDF para substituir o atual. Tamanho máximo: 10 MB.
                        </small>
                        @error('arquivo_pdf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-light border d-flex align-items-start gap-2 mb-4">
                        <i class="bi bi-info-circle mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Forma de envio</strong>
                            <div class="mt-1">
                                O boletim precisa ter uma avaliação escrita ou um documento PDF anexado.
                            </div>
                        </div>
                    </div>

                    <div class="usuario-form-actions">
                        <a href="{{ route('boletins.show', $boletim) }}" class="usuario-button usuario-button-muted">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="usuario-button usuario-button-primary">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            Salvar alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoPeriodo = document.getElementById('tipo_periodo');
            const numeroPeriodo = document.getElementById('numero_periodo');
            const quantidadePorTipo = {
                bimestre: 4,
                trimestre: 3,
                semestre: 2
            };

            tipoPeriodo.addEventListener('change', function () {
                const tipo = tipoPeriodo.value;
                const numeroAtual = Number(numeroPeriodo.value);
                numeroPeriodo.replaceChildren();

                for (let numero = 1; numero <= quantidadePorTipo[tipo]; numero++) {
                    const opcao = document.createElement('option');
                    opcao.value = numero;
                    opcao.textContent = `${numero}º ${tipo.charAt(0).toUpperCase()}${tipo.slice(1)}`;
                    opcao.selected = numero === numeroAtual;
                    numeroPeriodo.appendChild(opcao);
                }
            });
        });
    </script>
@endsection