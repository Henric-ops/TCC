@extends('layout.app')

@section('title', 'Editar turma')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/turma-edit.css') }}">
@endpush

@section('content')
    @php
        $professoresSelecionados = old('professores', $professoresVinculados ?? []);
    @endphp

    <div class="usuario-form-page turma-edit-page">
        <div class="usuario-form-header">
            <div class="turma-edit-heading">
                <a href="{{ route('admin.turmas.index') }}" class="usuario-button turma-edit-back">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Turmas
                </a>

                <div class="usuario-form-title">
                    <span class="usuario-form-title-icon" aria-hidden="true">
                        <i class="bi bi-pencil-square"></i>
                    </span>
                    <div>
                        <h1 class="h4 mb-1">Editar turma</h1>
                        <p class="usuario-form-subtitle">Atualize os dados e os professores vinculados.</p>
                    </div>
                </div>
            </div>

            <div class="turma-edit-summary">
                <i class="bi bi-people-fill turma-edit-summary-icon" aria-hidden="true"></i>
                <div>
                    <strong>{{ $turma->nome }}</strong>
                    <small>{{ $turma->ano }} · {{ $turma->periodo }}</small>
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

                <form action="{{ route('admin.turmas.update', $turma) }}" method="POST">
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
                            <div class="turma-edit-professores-header">
                                <label class="form-label mb-0">Professores responsáveis</label>
                                <span class="turma-edit-selected-count">
                                    {{ count($professoresSelecionados) }} selecionados
                                </span>
                            </div>

                            @if($professores->isEmpty())
                                <div class="usuario-form-checkbox-list mt-2">
                                    <small class="usuario-form-help mb-0">
                                        Nenhum professor aprovado ainda.
                                    </small>
                                </div>
                            @else
                                <div class="usuario-form-checkbox-list mt-2">
                                    @foreach($professores as $professor)
                                        @php
                                            $professorSelecionado = in_array($professor->id, $professoresSelecionados);
                                            $iniciais = collect(explode(' ', trim($professor->nome)))
                                                ->filter()
                                                ->take(2)
                                                ->map(fn ($parte) => strtoupper(substr($parte, 0, 1)))
                                                ->implode('');
                                        @endphp

                                        <label class="form-check usuario-form-checkbox" for="professor_{{ $professor->id }}" data-escola="{{ $professor->escola_id }}">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="professores[]"
                                                value="{{ $professor->id }}"
                                                id="professor_{{ $professor->id }}"
                                                {{ $professorSelecionado ? 'checked' : '' }}
                                            >
                                            <span class="turma-edit-professor-avatar" aria-hidden="true">
                                                {{ $iniciais }}
                                            </span>
                                            <span class="turma-edit-professor-info">
                                                <strong>{{ $professor->nome }}</strong>
                                                <small>Professor</small>
                                            </span>
                                            <span class="turma-edit-status {{ $professorSelecionado ? '' : 'is-available' }}">
                                                <i class="bi {{ $professorSelecionado ? 'bi-check-lg' : 'bi-circle' }}" aria-hidden="true"></i>
                                                {{ $professorSelecionado ? 'Selecionado' : 'Disponível' }}
                                            </span>
                                        </label>
                                    @endforeach
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
                        <button type="submit" class="usuario-button turma-edit-save">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            Salvar alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const escolaSelect = document.getElementById('escola_id');
        const professorLabels = document.querySelectorAll('.usuario-form-checkbox');

        function filtrarProfessoresPorEscola() {
            const escolaId = escolaSelect.value;

            professorLabels.forEach((label) => {
                const pertence = label.dataset.escola === escolaId;
                const escondido = escolaId !== '' && !pertence;

                label.style.display = escondido ? 'none' : '';

                if (escondido) {
                    label.querySelector('input[type=checkbox]').checked = false;
                }
            });
        }

        escolaSelect.addEventListener('change', filtrarProfessoresPorEscola);
        filtrarProfessoresPorEscola();
    </script>
@endsection