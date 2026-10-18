@extends('layout.app')

@section('title', 'Novo usuário')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-create.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/usuario-form.js') }}" defer></script>
    <script src="{{ asset('js/alunos-selecao.js') }}" defer></script>
@endpush

@section('content')
    <main class="usuario-form-page usuario-cadastro-page">
        <header class="usuario-cadastro-header">
            <div>
                <h1 class="usuario-cadastro-title">Novo usuário</h1>
                <p class="usuario-cadastro-subtitle">Cadastre um novo acesso para sua escola.</p>
            </div>
            <a href="{{ route('admin.usuarios.index') }}" class="usuario-button usuario-button-muted">
                Voltar
            </a>
        </header>

        <section class="card usuario-form-card usuario-cadastro-card">
            <div class="card-body">
                <div class="usuario-cadastro-section-heading">
                    <h2>Dados do usuário</h2>
                </div>
                <div class="usuario-cadastro-divider" aria-hidden="true"></div>
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Não foi possível cadastrar o usuário.</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.usuarios.store') }}">
                    @csrf

                    <div class="row g-3 usuario-cadastro-fields">
                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome"
                                class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome') }}" autocomplete="name" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" autocomplete="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" id="senha" name="senha"
                                class="form-control @error('senha') is-invalid @enderror"
                                autocomplete="new-password" required>
                            @error('senha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="perfil" class="form-label">Perfil</label>
                            <select name="perfil" id="perfil" class="form-select @error('perfil') is-invalid @enderror" required>
                                <option value="">Selecione o perfil</option>
                                <option value="professor" {{ old('perfil') === 'professor' ? 'selected' : '' }}>Professor</option>
                                <option value="responsavel" {{ old('perfil') === 'responsavel' ? 'selected' : '' }}>Responsável</option>
                            </select>
                            @error('perfil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                            <div id="bloco-escola-unica" class="col-md-6 mb-3 usuario-form-field">
                                <label for="escola_id" class="form-label">Escola</label>
                                <select name="escola_id" id="escola_id" class="form-select @error('escola_id') is-invalid @enderror">
                                    <option value="">Selecione</option>
                                    @foreach($escolas as $escola)
                                        <option value="{{ $escola->id }}" {{ old('escola_id', $usuario->escola_id ?? '') == $escola->id ? 'selected' : '' }}>
                                            {{ $escola->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('escola_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div id="bloco-professor" class="col-12 mb-3 usuario-form-field" style="display: none;">
                            <label class="form-label">Escolas em que atua</label>
                            <div class="usuario-form-checkbox-list mt-2">
                                @foreach($escolas as $escola)
                                    <label class="form-check usuario-form-checkbox" for="escola_{{ $escola->id }}">
                                        <input class="form-check-input" type="checkbox" name="escolas[]" value="{{ $escola->id }}"
                                            id="escola_{{ $escola->id }}"
                                            {{ in_array($escola->id, old('escolas', $escolasVinculadas ?? [])) ? 'checked' : '' }}>
                                        <span>{{ $escola->nome }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('escolas')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div id="bloco-responsavel" class="usuario-cadastro-related" hidden>
                        <div class="usuario-cadastro-section-heading">
                            <h2>Vínculo do responsável</h2>
                        </div>
                        <div class="usuario-cadastro-divider" aria-hidden="true"></div>

                        <div class="row g-3 usuario-cadastro-fields">
                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="buscar-aluno" class="form-label">Aluno(s) vinculado(s)</label>
                            <input type="search" id="buscar-aluno" class="form-control mb-2"
                                placeholder="Buscar aluno pelo nome..." autocomplete="off">

                            <div class="alunos-lista" id="alunos-lista" role="group" aria-label="Selecionar alunos">
                                @forelse($alunos as $aluno)
                                    <label class="aluno-item" data-nome="{{ $aluno->nome }}"
                                        data-escola-id="{{ $aluno->escola_id }}">
                                        <input type="checkbox" class="form-check-input @error('alunos') is-invalid @enderror"
                                            name="alunos[]" value="{{ $aluno->id }}"
                                            {{ in_array($aluno->id, old('alunos', [])) ? 'checked' : '' }}>
                                        <span>{{ $aluno->nome }}</span>
                                    </label>
                                @empty
                                    <p class="text-muted small mb-0">Nenhum aluno disponível.</p>
                                @endforelse
                            </div>

                            <small id="alunos-sem-resultado" class="text-muted" hidden>
                                Nenhum aluno encontrado.
                            </small>

                            @error('alunos')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('alunos.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="parentesco" class="form-label">Parentesco</label>
                            <select name="parentesco" id="parentesco" class="form-select @error('parentesco') is-invalid @enderror">
                                <option value="">Selecione o parentesco</option>
                                @foreach([
                                    'pai' => 'Pai', 'mae' => 'Mãe', 'avo' => 'Avô', 'ava' => 'Avó',
                                    'irmao' => 'Irmão', 'irma' => 'Irmã', 'tio' => 'Tio', 'tia' => 'Tia',
                                    'padrasto' => 'Padrasto', 'madrasta' => 'Madrasta',
                                    'tutor_legal' => 'Tutor(a) legal', 'outro' => 'Outro',
                                ] as $valor => $label)
                                    <option value="{{ $valor }}" {{ old('parentesco') === $valor ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parentesco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
                    </div>

                    <div class="usuario-form-actions">
                        <a href="{{ route('admin.usuarios.index') }}" class="usuario-button usuario-button-muted">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="usuario-button usuario-button-primary">
                            <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                            Cadastrar 
                        </button>
                    </div>
                </form>
            </div>
        </section>


        <script>
    const perfilSelect = document.getElementById('perfil');
    const blocoResponsavel = document.getElementById('bloco-responsavel');
    const blocoProfessor = document.getElementById('bloco-professor');
    const blocoEscolaUnica = document.getElementById('bloco-escola-unica');

    function toggleBlocos() {
        const perfil = perfilSelect.value;

        blocoResponsavel.style.display = perfil === 'responsavel' ? 'block' : 'none';
        blocoProfessor.style.display = perfil === 'professor' ? 'block' : 'none';
        blocoEscolaUnica.style.display = perfil === 'professor' ? 'none' : 'block';
    }

    perfilSelect.addEventListener('change', toggleBlocos);
    toggleBlocos();
</script>
    </main>
@endsection