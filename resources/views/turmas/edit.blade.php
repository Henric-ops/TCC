@extends('layout.app')

@section('title', 'Editar turma')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/turma-edit.css') }}">
    
@endpush
    <script src="{{ asset('js/turma.js') }}" defer></script>


@section('content')
    @php
        $professoresSelecionados = old('professores', $professoresVinculados ?? []);
    @endphp

    <div class="usuario-form-page turma-edit-page">
        <div class="usuario-form-header">
            <div class="turma-edit-heading">

                <div class="usuario-form-title">
                  
                    <div>
                        <h1 class="h4 mb-1">Editar turma</h1>
                        <p class="usuario-form-subtitle">Atualize os dados e os professores vinculados.</p>
                    </div>
                </div>
            </div>

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
                            <strong>Não foi possível atualizar a turma.</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.turmas.update', $turma) }}" method="POST" data-professores-sem-restricao-escola>
                    @csrf
                    @method('PUT')

                    <div class="row">
                      <div class="mb-3">
                                <label class="form-label">Escola</label>
                                <select name="escola_id" id="escola_id" class="form-select @error('escola_id') is-invalid @enderror">
                                    <option value="">Selecione</option>
                                    @foreach($escolas as $escola)
                                        <option value="{{ $escola->id }}" {{ old('escola_id', $turma->escola_id ?? '') == $escola->id ? 'selected' : '' }}>
                                            {{ $escola->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('escola_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome', $turma->nome) }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="ano" class="form-label">Ano</label>
                            <input type="number" id="ano" name="ano" class="form-control @error('ano') is-invalid @enderror"
                                value="{{ old('ano', $turma->ano) }}" required>
                            @error('ano')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="periodo" class="form-label">Período</label>
                            <select name="periodo" id="periodo" class="form-select @error('periodo') is-invalid @enderror" required>
                                <option value="">Selecione</option>
                                <option value="Manhã" {{ old('periodo', $turma->periodo) === 'Manhã' ? 'selected' : '' }}>Manhã</option>
                                <option value="Tarde" {{ old('periodo', $turma->periodo) === 'Tarde' ? 'selected' : '' }}>Tarde</option>
                                <option value="Integral" {{ old('periodo', $turma->periodo) === 'Integral' ? 'selected' : '' }}>Integral</option>
                            </select>
                            @error('periodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3 usuario-form-field">
                            <label id="professores-label" class="form-label">
                                Professores responsáveis
                            </label>

                            @if($professores->isEmpty())
                                <div class="usuario-form-checkbox-list mt-2">
                                    <small class="usuario-form-help mb-0">
                                        Nenhum professor aprovado ainda.
                                    </small>
                                </div>
                            @else
                                <div class="turma-professor-dropdown mt-2" id="professores-dropdown">
                                    <button
                                        type="button"
                                        class="form-select turma-professor-trigger"
                                        id="professores-trigger"
                                        aria-labelledby="professores-label professores-trigger"
                                        aria-expanded="false"
                                        aria-controls="professores-painel"
                                    >
                                        <span id="professores-resumo">
                                            {{ count($professoresSelecionados) ? count($professoresSelecionados) . ' selecionados' : 'Selecione' }}
                                        </span>
                                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                    </button>

                                    <div class="turma-professor-painel" id="professores-painel" hidden>
                                        <div class="turma-professor-search">
                                            <i class="bi bi-search" aria-hidden="true"></i>
                                            <input
                                                type="search"
                                                id="buscar-professor"
                                                placeholder="Buscar professor pelo nome"
                                                aria-label="Buscar professor"
                                                autocomplete="off"
                                            >
                                        </div>

                                        <div class="usuario-form-checkbox-list turma-professores-list">
                                            @foreach($professores as $professor)
                                                @php
                                                    $professorSelecionado = in_array(
                                                        $professor->id,
                                                        $professoresSelecionados
                                                    );
                                                @endphp

                                                <label
                                                    class="form-check usuario-form-checkbox professor-item"
                                                    for="professor_{{ $professor->id }}"
                                                    data-nome="{{ $professor->nome }}"
                                                    data-escolas="{{ $professor->escolas->pluck('id')->implode(',') }}"
                                                >
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="professores[]"
                                                        value="{{ $professor->id }}"
                                                        id="professor_{{ $professor->id }}"
                                                        {{ $professorSelecionado ? 'checked' : '' }}
                                                    >
                                                    <span class="turma-professor-name">{{ $professor->nome }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <small id="professores-sem-resultado" class="usuario-form-help" hidden>
                                            Nenhum professor encontrado.
                                        </small>
                                    </div>
                                </div>
                            @endif

                            @error('professores')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="usuario-form-actions">
                        <a href="{{ route('admin.turmas.index') }}" class="usuario-button usuario-button-muted">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="usuario-button usuario-button-primary">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            Salvar 
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection