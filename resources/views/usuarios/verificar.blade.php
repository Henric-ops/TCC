@extends('layout.app')

@section('title', 'Verificar cadastro')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/verificar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <script src="{{ asset('js/verificar.js') }}" defer></script>

    <div class="verificar-page">

        <div class="verificar-header">
            <div class="verificar-title">
                <span class="verificar-title-icon" aria-hidden="true">
                    <i class="bi bi-person-check-fill"></i>
                </span>
                <div>
                    <h1 class="h4 mb-1">Validar cadastro</h1>
                    <p class="verificar-subtitle">Analise os dados antes de aprovar o usuário.</p>
                </div>
            </div>

            <a href="{{ route('admin.usuarios.index') }}" class="usuario-button usuario-button-muted">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Voltar para usuários
            </a>
        </div>

        <div class="card verificar-card">

            <div class="card-header">
                <i class="bi bi-person-vcard" aria-hidden="true"></i>
                <strong>Dados do usuário</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3 verificar-field">
                        <label class="form-label">Nome</label>

                        <input type="text" class="form-control" value="{{ $usuario->nome }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3 verificar-field">
                        <label class="form-label">E-mail</label>

                        <input type="email" class="form-control" value="{{ $usuario->email }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3 verificar-field">
                        <label class="form-label">Escola</label>

                        <input type="text" class="form-control" value="{{ $usuario->escola?->nome ?? 'Não vinculada' }}"
                            disabled>
                    </div>

                    <div class="col-md-6 mb-3 verificar-field">
                        <label class="form-label">Perfil</label>

                        <input type="text" class="form-control text-capitalize" value="{{ $usuario->perfil }}" disabled>
                    </div>

                </div>

                <hr>


                @if($usuario->perfil === 'responsavel')<!-- Se responsável exibe o formulário de vínculo com o aluno -->

                    <h5 class="verificar-section-title mb-3">
                        <i class="bi bi-people-fill" aria-hidden="true"></i>
                        Vínculo do responsável
                    </h5>

                    <form method="POST" action="{{ route('admin.usuarios.aprovar', $usuario) }}">

                        @csrf

                        <div class="mb-3 verificar-field">

                            <label class="form-label" for="alunos">
                                Alunos
                            </label>

                            <input type="search" class="form-control mb-2" id="buscar-aluno"
                                placeholder="Buscar aluno" autocomplete="off" data-aluno-search>

                            <div class="border rounded p-3" style="max-height: 15rem; overflow-y: auto;">
                                @foreach($alunos as $aluno)
                                    <div class="form-check" data-aluno-option>
                                        <input class="form-check-input @error('alunos') is-invalid @enderror"
                                            type="checkbox" name="alunos[]" value="{{ $aluno->id }}"
                                            id="aluno_{{ $aluno->id }}"
                                            {{ in_array($aluno->id, old('alunos', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="aluno_{{ $aluno->id }}">
                                            {{ $aluno->nome }}
                                        </label>
                                    </div>
                                @endforeach
                                <p class="text-muted mb-0 d-none" data-aluno-empty>Nenhum aluno encontrado.</p>
                            </div>

                            @error('alunos')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="mb-3 verificar-field">

                            <label class="form-label">
                                Parentesco
                            </label>

                            <select name="parentesco" class="form-select @error('parentesco') is-invalid @enderror" required>
                                <option value="">Selecione o parentesco</option>
                                @foreach([
                                        'pai' => 'Pai',
                                        'mae' => 'Mãe',
                                        'avo' => 'Avô',
                                        'ava' => 'Avó',
                                        'irmao' => 'Irmão',
                                        'irma' => 'Irmã',
                                        'tio' => 'Tio',
                                        'tia' => 'Tia',
                                        'padrasto' => 'Padrasto',
                                        'madrasta' => 'Madrasta',
                                        'tutor_legal' => 'Tutor(a) legal',
                                        'outro' => 'Outro',
                                    ] as $valor => $label)
                                    <option value="{{ $valor }}" {{ old('parentesco') === $valor ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            @error('parentesco')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="verificar-actions">

                            <button type="submit" formaction="{{ route('admin.usuarios.recusar', $usuario) }}" formnovalidate
                                class="usuario-button usuario-button-danger" data-confirm-rejection>
                                <i class="bi bi-x-circle" aria-hidden="true"></i>
                                Recusar
                            </button>

                            <button type="submit" class="usuario-button usuario-button-primary">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                Aprovar cadastro
                            </button>

                        </div>

                    </form>


                @elseif($usuario->perfil === 'professor')<!-- Se professor, exibe o formulário de vínculo com a turma -->

                    <h5 class="verificar-section-title mb-3">
                        <i class="bi bi-easel2-fill" aria-hidden="true"></i>
                        Vínculo do professor
                    </h5>

                    <form method="POST" action="{{ route('admin.usuarios.aprovar', $usuario) }}">

                        @csrf

                        <div class="mb-3 verificar-field">

                            <label class="form-label" for="filtro-escola-turma">
                                Escola
                            </label>

                            <select id="filtro-escola-turma" class="form-select mb-2" data-turma-school-filter>
                                <option value="">Todas as escolas</option>
                                @foreach($turmas->pluck('escola')->unique('id')->sortBy('nome') as $escola)
                                    <option value="{{ $escola->id }}">{{ $escola->nome }}</option>
                                @endforeach
                            </select>

                            <label class="form-label" for="buscar-turma">
                                Turmas
                            </label>

                            <input type="search" class="form-control mb-2" id="buscar-turma"
                                placeholder="Buscar turma" autocomplete="off" data-turma-search>

                            <div class="border rounded p-3" style="max-height: 15rem; overflow-y: auto;">
                                @foreach($turmas as $turma)
                                    <div class="form-check" data-turma-option data-school-id="{{ $turma->escola_id }}">
                                        <input class="form-check-input @error('turmas') is-invalid @enderror"
                                            type="checkbox" name="turmas[]" value="{{ $turma->id }}"
                                            id="turma_{{ $turma->id }}"
                                            {{ in_array($turma->id, old('turmas', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="turma_{{ $turma->id }}">
                                            {{ $turma->nome }} - {{ $turma->escola->nome }}
                                        </label>
                                    </div>
                                @endforeach
                                <p class="text-muted mb-0 d-none" data-turma-empty>Nenhuma turma encontrada.</p>
                            </div>

                            @error('turmas')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="verificar-actions">

                            <button type="submit" formaction="{{ route('admin.usuarios.recusar', $usuario) }}" formnovalidate
                                class="usuario-button usuario-button-danger" data-confirm-rejection>
                                <i class="bi bi-x-circle" aria-hidden="true"></i>
                                Recusar
                            </button>

                            <button type="submit" class="usuario-button usuario-button-primary">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                Aprovar cadastro
                            </button>

                        </div>

                    </form>

                @endif

            </div>

        </div>

    </div>

@endsection