@extends('layout.app')

@section('title', 'Novo aluno')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <script src="{{ asset('js/aluno-create.js') }}"></script>

    <div class="usuario-form-page">
        <div class="usuario-form-header">
            <div class="usuario-form-title">
                <span class="usuario-form-title-icon" aria-hidden="true">
                    <i class="bi bi-person-plus-fill"></i>
                </span>

                <div>
                    <h1 class="h4 mb-1">Novo aluno</h1>
                    <p class="usuario-form-subtitle">
                        Cadastre um aluno e organize seu vínculo escolar.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.alunos.index') }}" class="usuario-button usuario-button-muted">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Voltar para alunos
            </a>
        </div>

        <div class="card usuario-form-card">
            <div class="card-header">
                <i class="bi bi-person-vcard" aria-hidden="true"></i>
                <strong>Dados do aluno</strong>
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>

                        <div>
                            <strong>Não foi possível cadastrar o aluno.</strong>

                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.alunos.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="nome" class="form-label">
                                Nome
                            </label>

                            <input type="text" id="nome" name="nome"
                                class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}"
                                autocomplete="name" required>

                            @error('nome')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="data_nascimento" class="form-label">
                                Data de nascimento
                            </label>

                            <input type="date" id="data_nascimento" name="data_nascimento"
                                class="form-control @error('data_nascimento') is-invalid @enderror"
                                value="{{ old('data_nascimento') }}" required>

                            @error('data_nascimento')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="escola_id" class="form-label">
                                Escola
                            </label>

                            <select name="escola_id" id="escola_id"
                                class="form-select @error('escola_id') is-invalid @enderror" required>
                                <option value="">
                                    Selecione a escola
                                </option>

                                @foreach($escolas as $escola)
                                    <option value="{{ $escola->id }}" {{ old('escola_id') == $escola->id ? 'selected' : '' }}>
                                        {{ $escola->nome }}
                                    </option>
                                @endforeach
                            </select>

                            @error('escola_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label class="form-label">
                                Turma(s)
                            </label>

                            <div class="turma-multiselect" id="turmaMultiselect">

                                <button type="button" class="turma-multiselect-trigger" id="turmaTrigger"
                                    aria-expanded="false">
                                    <span id="turmaTriggerText">
                                        Selecione uma escola primeiro
                                    </span>

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
                                                                                    <span class="turma-option-nome">
                                                                                        {{ $turma->nome }}
                                                                                    </span>

                                                                                    <span class="turma-option-periodo">
                                                                                        {{ $turma->periodo }}
                                                                                    </span>
                                                                                </span>
                                                                            </label>
                                        @endforeach

                                        <div class="turma-empty" id="turmaEmpty">
                                            Nenhuma turma encontrada.
                                        </div>

                                    </div>

                                    <div class="turma-dropdown-footer">
                                        <span id="turmaCount">
                                            Nenhuma turma selecionada
                                        </span>
                                    </div>

                                </div>
                            </div>

                            @error('turmas')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="usuario-form-actions">

                        <a href="{{ route('admin.alunos.index') }}" class="usuario-button usuario-button-danger">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>

                        <button type="submit" class="usuario-button usuario-button-primary">
                            <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                            Cadastrar aluno
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('admin.alunos.store') }}"]');
            const escolaSelect = document.getElementById('escola_id');
            const trigger = document.getElementById('turmaTrigger');

            if (!form || !escolaSelect || !trigger) {
                return;
            }

            form.addEventListener('submit', function (event) {
                const escolaValida = escolaSelect.value !== '';
                const turmaSelecionada = document.querySelector('input[name="turmas[]"]:checked');

                if (!escolaValida || !turmaSelecionada) {
                    event.preventDefault();

                    if (!escolaValida) {
                        escolaSelect.focus();
                        escolaSelect.classList.add('is-invalid');
                    }

                    if (!turmaSelecionada) {
                        trigger.style.borderColor = '#dc3545';
                        trigger.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.15)';
                        trigger.setAttribute('aria-invalid', 'true');
                        trigger.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    </script>
@endsection