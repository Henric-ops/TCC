@extends('layout.app')

@section('title', 'Editar aluno')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aluno-create.css') }}">
    <script src="{{ asset('js/aluno-create.js') }}"></script>

    <div class="usuario-form-page aluno-form-page">
        <div class="aluno-create-header">
            <div>
                <h1 class="aluno-create-title">Editar aluno</h1>
                <p class="aluno-create-subtitle">Atualize os dados pessoais e o vínculo escolar do aluno.</p>
            </div>

            <a href="{{ route('admin.alunos.index') }}" class="usuario-button usuario-button-muted">
                Voltar
            </a>
        </div>

        <div class="card usuario-form-card aluno-create-card">
            <div class="card-body">
                <div class="aluno-create-section-heading">
                    <h2>Dados do aluno</h2>
                </div>

                <div class="aluno-create-divider" aria-hidden="true"></div>

                @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                                <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
                                <div>
                                    <strong>Não foi possível atualizar o aluno.</strong>
                                    <ul class="mb-0 mt-1 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.alunos.update', $aluno) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3 aluno-create-fields">
                                <div class="col-md-6 mb-3 usuario-form-field">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" id="nome" name="nome"
                                        class="form-control @error('nome') is-invalid @enderror"
                                        value="{{ old('nome', $aluno->nome) }}" autocomplete="name" required>
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3 usuario-form-field">
                                    <label for="data_nascimento" class="form-label">Data de nascimento</label>
                                    <input type="date" id="data_nascimento" name="data_nascimento"
                                        class="form-control @error('data_nascimento') is-invalid @enderror"
                                        value="{{ old('data_nascimento', $aluno->data_nascimento?->format('Y-m-d')) }}" required>
                                    @error('data_nascimento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3 usuario-form-field">
                                    <label for="escola_id" class="form-label">Escola</label>
                                    <select name="escola_id" id="escola_id"
                                        class="form-select @error('escola_id') is-invalid @enderror" required>
                                        <option value="">Selecione a escola</option>
                                        @foreach($escolas as $escola)
                                            <option value="{{ $escola->id }}" {{ old('escola_id', $aluno->escola_id) == $escola->id ? 'selected' : '' }}>
                                                {{ $escola->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('escola_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3 usuario-form-field">
                                    <label for="turmaTrigger" class="form-label">Turma(s)</label>
                                    <div class="turma-multiselect" id="turmaMultiselect">
                                        <button type="button" class="turma-multiselect-trigger" id="turmaTrigger"
                                            aria-expanded="false">
                                            <span id="turmaTriggerText">Selecione uma escola primeiro</span>
                                            <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                        </button>

                                        <div class="turma-dropdown" id="turmaDropdown">
                                            <div class="turma-search">
                                                <i class="bi bi-search" aria-hidden="true"></i>
                                                <input type="text" id="turmaSearch" placeholder="Buscar turma..."
                                                    autocomplete="off">
                                            </div>

                                            <div class="turma-options" id="turmaOptions">
                                                @foreach($turmas as $turma)
                                                    <label class="turma-option" data-escola="{{ $turma->escola_id }}"
                                                        data-search="{{ strtolower($turma->nome . ' ' . $turma->periodo) }}">
                                                        <input type="radio" name="turmas[]" value="{{ $turma->id }}"
                                                            class="turma-checkbox" required {{ in_array(
                                                                $turma->id,
                                                                old('turmas', $turmasVinculadas ?? [])
                                                            ) ? 'checked' : '' }}>
                                                        <span class="turma-option-check">
                                                            <i class="bi bi-check-lg"></i>
                                                        </span>
                                                        <span class="turma-option-info">
                                                            <span class="turma-option-nome">{{ $turma->nome }}</span>
                                                            <span class="turma-option-periodo">{{ $turma->periodo }}</span>
                                                        </span>
                                                    </label>
                                                @endforeach

                                                <div class="turma-empty" id="turmaEmpty">
                                                    Nenhuma turma encontrada.
                                                </div>
                                            </div>

                                            <div class="turma-dropdown-footer">
                                                <span id="turmaCount">Nenhuma turma selecionada</span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('turmas')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="usuario-form-actions">
                                <a href="{{ route('admin.alunos.index') }}" class="usuario-button usuario-button-danger">
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
@endsection