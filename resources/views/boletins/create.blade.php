```blade
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

                    <p class="usuario-form-subtitle">
                        Registre a avaliação do aluno ou envie o boletim em PDF.
                    </p>
                </div>

            </div>

            <a href="{{ route('boletins.index') }}"
               class="usuario-button usuario-button-muted">

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

                    <div class="alert alert-danger d-flex align-items-start gap-2"
                         role="alert">

                        <i class="bi bi-exclamation-triangle-fill mt-1"
                           aria-hidden="true"></i>

                        <div>

                            <strong>
                                Não foi possível cadastrar o boletim.
                            </strong>

                            <ul class="mb-0 mt-1 ps-3">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                @endif


                <form action="{{ route('boletins.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf


                    <!-- ESCOLA / TURMA -->
                    <div class="row">

                        <!-- Escola -->
                        <div class="col-md-6 mb-3 usuario-form-field">

                            <label for="escola_id" class="form-label">
                                Escola
                            </label>

                            <select
                                name="escola_id"
                                id="escola_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Selecione uma escola
                                </option>

                                @foreach ($escolas as $escola)

                                    <option value="{{ $escola->id }}">
                                        {{ $escola->nome }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- Turma -->
                        <div class="col-md-6 mb-3 usuario-form-field">

                            <label for="turma_id" class="form-label">
                                Turma
                            </label>

                            <select
                                name="turma_id"
                                id="turma_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Selecione uma turma
                                </option>

                                @foreach ($turmas as $turma)

                                    <option
                                        value="{{ $turma->id }}"
                                        data-escola="{{ $turma->escola_id }}"
                                    >
                                        {{ $turma->nome }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    <!-- ALUNO / ANO -->
                    <div class="row">

                        <!-- Aluno -->
                        <div class="col-md-6 mb-3 usuario-form-field">

                            <label for="aluno_id" class="form-label">
                                Aluno
                            </label>

                            <select
                                name="aluno_id"
                                id="aluno_id"
                                class="form-select @error('aluno_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Selecione um aluno
                                </option>

                                @foreach ($alunos as $aluno)

                                    <option
                                        value="{{ $aluno->id }}"
                                        data-escola="{{ $aluno->escola_id }}"
                                        data-turmas="{{ $aluno->turmas->pluck('id')->implode(',') }}"
                                        {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}
                                    >
                                        {{ $aluno->nome }}
                                    </option>

                                @endforeach

                            </select>

                            @error('aluno_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Ano --}}
                        <div class="col-md-6 mb-3 usuario-form-field">

                            <label for="ano" class="form-label">
                                Ano
                            </label>

                            <input
                                type="text"
                                id="ano"
                                class="form-control"
                                value="{{ $anoAtual }}"
                                readonly
                            >

                            <small class="usuario-form-help">
                                Ano definido automaticamente pelo sistema.
                            </small>

                        </div>

                    </div>


                    <!-- PERÍODO -->
                    <div class="row">

                        {{-- Tipo --}}
                        <div class="col-md-6 mb-3 usuario-form-field">

                            <label for="tipo_periodo" class="form-label">
                                Regime Letivo
                            </label>

                            <select
                                name="tipo_periodo"
                                id="tipo_periodo"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Selecione
                                </option>

                                <option value="bimestre">
                                    Bimestre
                                </option>

                                <option value="trimestre">
                                    Trimestre
                                </option>

                                <option value="semestre">
                                    Semestre
                                </option>

                            </select>

                            <small class="usuario-form-help">
                                Escolha como o boletim será dividido.
                            </small>

                        </div>


                        {{-- Número --}}
                        <div class="col-md-6 mb-3 usuario-form-field">

                            <label for="numero_periodo" class="form-label">
                                Período
                            </label>

                            <select
                                name="numero_periodo"
                                id="numero_periodo"
                                class="form-select"
                                required
                                disabled
                            >

                                <option value="">
                                    Selecione primeiro o tipo
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- AVALIAÇÃO -->
                    <div class="mb-3 usuario-form-field">

                        <label for="observacao" class="form-label">
                             Avaliação
                        </label>

                        <textarea
                            name="observacao"
                            id="observacao"
                            rows="8"
                            class="form-control @error('observacao') is-invalid @enderror"
                            placeholder="Escreva aqui a avaliação ou observações sobre o aluno..."
                        >{{ old('observacao') }}</textarea>

                        

                        @error('observacao')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- PDF --}}
                    <div class="mb-3 usuario-form-field">

                        <label for="arquivo_pdf" class="form-label">
                            Documento do boletim
                        </label>

                        <input
                            type="file"
                            name="arquivo_pdf"
                            id="arquivo_pdf"
                            class="form-control @error('arquivo_pdf') is-invalid @enderror"
                            accept=".pdf,application/pdf"
                        >

                        <small class="usuario-form-help">
                            Opcional. Envie um PDF caso o boletim já esteja pronto.
                            Tamanho máximo: 10 MB.
                        </small>

                        @error('arquivo_pdf')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                     <!-- informações -->
                    <div class="alert alert-light border d-flex align-items-start gap-2 mb-4">

                        <i class="bi bi-info-circle mt-1" aria-hidden="true"></i>

                        <div>

                            <strong>
                                Forma de envio
                            </strong>

                            <div class="mt-1">
                                Você pode escrever a avaliação no sistema,
                                anexar um PDF ou utilizar as duas opções.
                                Pelo menos uma delas deve ser preenchida.
                            </div>

                        </div>

                    </div>


                    <!-- ações -->
                    <div class="usuario-form-actions">

                        <a href="{{ route('boletins.index') }}"
                           class="usuario-button usuario-button-muted">

                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar

                        </a>


                        <button type="submit"
                                class="usuario-button usuario-button-primary">

                            <i class="bi bi-file-earmark-plus"
                               aria-hidden="true"></i>

                            Salvar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const escolaSelect = document.getElementById('escola_id');
            const turmaSelect = document.getElementById('turma_id');
            const alunoSelect = document.getElementById('aluno_id');

            const turmaOptions = Array.from(
                turmaSelect.querySelectorAll('option[data-escola]')
            );

            const alunoOptions = Array.from(
                alunoSelect.querySelectorAll('option[data-escola]')
            );


            function atualizarTurmas() {

                const escolaId = escolaSelect.value;

                turmaSelect.value = '';

                turmaSelect.querySelectorAll('option[data-escola]')
                    .forEach(option => {

                        option.style.display =
                            option.dataset.escola === escolaId
                                ? ''
                                : 'none';

                    });

                atualizarAlunos();

            }


            function atualizarAlunos() {

                const escolaId = escolaSelect.value;
                const turmaId = turmaSelect.value;

                alunoSelect.value = '';

                alunoSelect.querySelectorAll('option[data-escola]')
                    .forEach(option => {

                        const turmas =
                            option.dataset.turmas
                                ? option.dataset.turmas.split(',')
                                : [];

                        const escolaCorreta =
                            option.dataset.escola === escolaId;

                        const turmaCorreta =
                            turmaId === '' || turmas.includes(turmaId);

                        option.style.display =
                            escolaCorreta && turmaCorreta
                                ? ''
                                : 'none';

                    });

            }


            escolaSelect.addEventListener(
                'change',
                atualizarTurmas
            );

            turmaSelect.addEventListener(
                'change',
                atualizarAlunos
            );


            // Períodos
            const tipoPeriodo =
                document.getElementById('tipo_periodo');

            const numeroPeriodo =
                document.getElementById('numero_periodo');


            function atualizarPeriodos() {

                const tipo = tipoPeriodo.value;

                numeroPeriodo.innerHTML = '';

                if (!tipo) {

                    numeroPeriodo.disabled = true;

                    numeroPeriodo.innerHTML =
                        '<option value="">Selecione primeiro o tipo</option>';

                    return;
                }


                numeroPeriodo.disabled = false;

                let quantidade = 0;

                if (tipo === 'bimestre') {
                    quantidade = 4;
                }

                if (tipo === 'trimestre') {
                    quantidade = 3;
                }

                if (tipo === 'semestre') {
                    quantidade = 2;
                }


                const nomes = {
                    bimestre: 'Bimestre',
                    trimestre: 'Trimestre',
                    semestre: 'Semestre'
                };


                numeroPeriodo.innerHTML =
                    '<option value="">Selecione</option>';


                for (let i = 1; i <= quantidade; i++) {

                    const option =
                        document.createElement('option');

                    option.value = i;

                    option.textContent =
                        i + 'º ' + nomes[tipo];

                    numeroPeriodo.appendChild(option);

                }

            }


            tipoPeriodo.addEventListener(
                'change',
                atualizarPeriodos
            );

            const escolaAnterior =
                "{{ old('escola_id') }}";

            const turmaAnterior =
                "{{ old('turma_id') }}";

            const periodoAnterior =
                "{{ old('tipo_periodo') }}";

            const numeroAnterior =
                "{{ old('numero_periodo') }}";


            if (escolaAnterior) {

                escolaSelect.value = escolaAnterior;

                atualizarTurmas();

            }


            if (turmaAnterior) {

                turmaSelect.value = turmaAnterior;

                atualizarAlunos();

            }


            if (periodoAnterior) {

                tipoPeriodo.value = periodoAnterior;

                atualizarPeriodos();

                numeroPeriodo.value = numeroAnterior;

            }

        });

    </script>

@endsection
