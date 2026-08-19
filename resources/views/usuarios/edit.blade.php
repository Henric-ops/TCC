@extends('layout.app')

@section('title', 'Editar usuário')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <script src="{{ asset('js/usuario-form.js') }}" defer></script>

    @php
        $perfilAtual = old('perfil', $usuario->perfil);
        $alunosVinculadosAtuais = old('alunos', $alunosVinculados);
        $parentescoAtual = old('parentesco', $usuario->alunosResponsavel->first()?->pivot?->parentesco);
    @endphp

    <div class="usuario-form-page">
        <div class="usuario-form-header">
            <div class="usuario-form-title">
                <span class="usuario-form-title-icon" aria-hidden="true">
                    <i class="bi bi-pencil-square"></i>
                </span>
                <div>
                    <h1 class="h4 mb-1">Editar usuário</h1>
                    <p class="usuario-form-subtitle">Atualize os dados e os vínculos deste usuário.</p>
                </div>
            </div>

            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Voltar para usuários
            </a>
        </div>

        <div class="card usuario-form-card">
            <div class="card-header">
                <i class="bi bi-person-vcard" aria-hidden="true"></i>
                <strong>Dados do usuário</strong>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Não foi possível atualizar o usuário.</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome"
                                class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome', $usuario->nome) }}" autocomplete="name" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $usuario->email) }}" autocomplete="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="senha" class="form-label">Nova senha</label>
                            <input type="password" id="senha" name="senha"
                                class="form-control @error('senha') is-invalid @enderror"
                                autocomplete="new-password">
                            <small class="usuario-form-help">Deixe em branco para manter a senha atual.</small>
                            @error('senha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="perfil" class="form-label">Perfil</label>
                            <select name="perfil" id="perfil" class="form-select @error('perfil') is-invalid @enderror" required>
                                <option value="">Selecione o perfil</option>
                                <option value="professor" {{ $perfilAtual === 'professor' ? 'selected' : '' }}>Professor</option>
                                <option value="responsavel" {{ $perfilAtual === 'responsavel' ? 'selected' : '' }}>Responsável</option>
                            </select>
                            @error('perfil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="escola_id" class="form-label">Escola</label>
                            <select name="escola_id" id="escola_id" class="form-select @error('escola_id') is-invalid @enderror" required>
                                <option value="">Selecione a escola</option>
                                @foreach($escolas as $escola)
                                    <option value="{{ $escola->id }}" {{ old('escola_id', $usuario->escola_id) == $escola->id ? 'selected' : '' }}>
                                        {{ $escola->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('escola_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="bloco-responsavel" hidden>
                        <hr>
                        <h2 class="usuario-form-section mb-3">
                            <i class="bi bi-people-fill" aria-hidden="true"></i>
                            Vínculo do responsável
                        </h2>

                        <div class="row">
                            <div class="col-md-7 mb-3 usuario-form-field">
                                <label for="alunos" class="form-label">Aluno(s) vinculado(s)</label>
                                <select name="alunos[]" id="alunos" multiple class="form-select usuario-form-alunos @error('alunos') is-invalid @enderror">
                                    @foreach($alunos as $aluno)
                                        <option value="{{ $aluno->id }}" data-escola-id="{{ $aluno->escola_id }}"
                                            {{ in_array($aluno->id, $alunosVinculadosAtuais) ? 'selected' : '' }}>
                                            {{ $aluno->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($alunos->isEmpty())
                                    <small class="usuario-form-help">Nenhum aluno cadastrado nesta escola.</small>
                                @else
                                    <small class="usuario-form-help">Segure Ctrl ou Cmd para selecionar mais de um aluno.</small>
                                @endif
                                @error('alunos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-5 mb-3 usuario-form-field">
                                <label for="parentesco" class="form-label">Parentesco</label>
                                <select name="parentesco" id="parentesco" class="form-select @error('parentesco') is-invalid @enderror">
                                    <option value="">Selecione o parentesco</option>
                                    @foreach([
                                        'pai' => 'Pai', 'mae' => 'Mãe', 'avo' => 'Avô', 'ava' => 'Avó',
                                        'irmao' => 'Irmão', 'irma' => 'Irmã', 'tio' => 'Tio', 'tia' => 'Tia',
                                        'padrasto' => 'Padrasto', 'madrasta' => 'Madrasta',
                                        'tutor_legal' => 'Tutor(a) legal', 'outro' => 'Outro',
                                    ] as $valor => $label)
                                        <option value="{{ $valor }}" {{ $parentescoAtual === $valor ? 'selected' : '' }}>
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
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-danger">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            Salvar alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection