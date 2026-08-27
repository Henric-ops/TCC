@extends('layout.app')

@section('title', 'Novo aluno')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">

    <div class="usuario-form-page">
        <div class="usuario-form-header">
            <div class="usuario-form-title">
                <span class="usuario-form-title-icon" aria-hidden="true">
                    <i class="bi bi-person-plus-fill"></i>
                </span>
                <div>
                    <h1 class="h4 mb-1">Novo aluno</h1>
                    <p class="usuario-form-subtitle">Cadastre um aluno e organize seu vínculo escolar.</p>
                </div>
            </div>

            <a href="{{ route('admin.alunos.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
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
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome') }}" autocomplete="name" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="data_nascimento" class="form-label">Data de nascimento</label>
                            <input type="date" id="data_nascimento" name="data_nascimento"
                                class="form-control @error('data_nascimento') is-invalid @enderror"
                                value="{{ old('data_nascimento') }}" required>
                            @error('data_nascimento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="escola_id" class="form-label">Escola</label>
                            <select name="escola_id" id="escola_id" class="form-select @error('escola_id') is-invalid @enderror" required>
                                <option value="">Selecione a escola</option>
                                @foreach($escolas as $escola)
                                    <option value="{{ $escola->id }}" {{ old('escola_id') == $escola->id ? 'selected' : '' }}>
                                        {{ $escola->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('escola_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="turmas" class="form-label">Turma(s)</label>
                            <select name="turmas[]" id="turmas" multiple class="form-select @error('turmas') is-invalid @enderror">
                                @foreach($turmas as $turma)
                                    <option value="{{ $turma->id }}" {{ in_array($turma->id, old('turmas', [])) ? 'selected' : '' }}>
                                        {{ $turma->nome }} — {{ $turma->periodo }}
                                    </option>
                                @endforeach
                            </select>
                            @if($turmas->isEmpty())
                                <small class="usuario-form-help">Nenhuma turma cadastrada ainda.</small>
                            @else
                                <small class="usuario-form-help">Segure Ctrl ou Cmd para selecionar mais de uma turma.</small>
                            @endif
                            @error('turmas')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="usuario-form-actions">
                        <a href="{{ route('admin.alunos.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                            Cadastrar aluno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection