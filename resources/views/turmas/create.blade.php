@extends('layout.app')

@section('title', 'Nova turma')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">

    <div class="usuario-form-page">
        <div class="usuario-form-header">
            <div class="usuario-form-title">
                <span class="usuario-form-title-icon" aria-hidden="true">
                    <i class="bi bi-easel2-fill"></i>
                </span>
                <div>
                    <h1 class="h4 mb-1">Nova turma</h1>
                    <p class="usuario-form-subtitle">Cadastre uma turma e organize seus vínculos.</p>
                </div>
            </div>

            <a href="{{ route('admin.turmas.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Voltar para turmas
            </a>
        </div>

        <div class="card usuario-form-card">
            <div class="card-header">
                <i class="bi bi-people-fill" aria-hidden="true"></i>
                <strong>Dados da turma</strong>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Não foi possível cadastrar a turma.</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.turmas.store') }}" method="POST">
                    @csrf

                    <div class="row">
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
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome') }}" placeholder="Ex: Turminha do Sol" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="ano" class="form-label">Ano</label>
                            <input type="number" id="ano" name="ano" class="form-control @error('ano') is-invalid @enderror"
                                value="{{ old('ano', date('Y')) }}" required>
                            @error('ano')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="periodo" class="form-label">Período</label>
                            <select name="periodo" id="periodo" class="form-select @error('periodo') is-invalid @enderror" required>
                                <option value="">Selecione</option>
                                <option value="Manhã" {{ old('periodo') === 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                <option value="Tarde" {{ old('periodo') === 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                <option value="Integral" {{ old('periodo') === 'Integral' ? 'selected' : '' }}>Integral</option>
                            </select>
                            @error('periodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       <div class="col-12 mb-3 usuario-form-field">
    <label class="form-label">Professor(es)</label>

    @if($professores->isEmpty())
        <div class="usuario-form-checkbox-list">
            <small class="usuario-form-help">
                Nenhum professor aprovado ainda.
            </small>
        </div>
    @else
        <div class="usuario-form-checkbox-list">
            @foreach($professores as $professor)
                <div class="form-check usuario-form-checkbox">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="professores[]"
                        value="{{ $professor->id }}"
                        id="professor_{{ $professor->id }}"
                        {{ in_array($professor->id, old('professores', $professoresVinculados ?? [])) ? 'checked' : '' }}
                    >

                    <label
                        class="form-check-label"
                        for="professor_{{ $professor->id }}"
                    >
                        {{ $professor->nome }}
                    </label>
                </div>
            @endforeach
        </div>

        <small class="usuario-form-help">
            Selecione um ou mais professores responsáveis pela turma.
        </small>
    @endif

    @error('professores')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
                    </div>

                    <div class="usuario-form-actions">
                        <a href="{{ route('admin.turmas.index') }}" class="btn btn-danger">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            Salvar turma
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection