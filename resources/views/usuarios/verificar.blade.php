@extends('layout.app')

@section('title', 'Verificar cadastro')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/verificar.css') }}">
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

            <a href="{{ route('admin.usuarios.index') }}"
                class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
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

                        <input type="text" class="form-control" value="{{ $usuario->escola->nome }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3 verificar-field">
                        <label class="form-label">Perfil</label>

                        <input type="text" class="form-control text-capitalize" value="{{ $usuario->perfil }}" disabled>
                    </div>

                </div>

                <hr>


                @if($usuario->perfil === 'responsavel')

                    <h5 class="verificar-section-title mb-3">
                        <i class="bi bi-people-fill" aria-hidden="true"></i>
                        Vínculo do responsável
                    </h5>

                    <form method="POST" action="{{ route('admin.usuarios.aprovar', $usuario) }}">

                        @csrf

                        <div class="mb-3 verificar-field">

                            <label class="form-label">
                                Aluno
                            </label>

                            <select name="aluno_id" class="form-select @error('aluno_id') is-invalid @enderror" required>

                                <option value="">
                                    Selecione o aluno
                                </option>

                                @foreach($alunos as $aluno)

                                    @if($aluno->escola_id === $usuario->escola_id)

                                        <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                                            {{ $aluno->nome }}
                                        </option>

                                    @endif

                                @endforeach

                            </select>

                            @error('aluno_id')
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

                            <button type="submit" formaction="{{ route('admin.usuarios.recusar', $usuario) }}"
                                class="btn btn-outline-danger" data-confirm-rejection>
                                <i class="bi bi-x-circle" aria-hidden="true"></i>
                                Recusar
                            </button>

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                Aprovar cadastro
                            </button>

                        </div>

                    </form>

              
                @elseif($usuario->perfil === 'professor')

                    <h5 class="verificar-section-title mb-3">
                        <i class="bi bi-easel2-fill" aria-hidden="true"></i>
                        Vínculo do professor
                    </h5>

                    <form method="POST" action="{{ route('admin.usuarios.aprovar', $usuario) }}">

                        @csrf

                        <div class="mb-3 verificar-field">

                            <label class="form-label">
                                Turma
                            </label>

                            <select name="turma_id" class="form-select @error('turma_id') is-invalid @enderror" required>

                                <option value="">
                                    Selecione a turma
                                </option>

                                @foreach($turmas as $turma)

                                    @if($turma->escola_id === $usuario->escola_id)

                                        <option value="{{ $turma->id }}">
                                            {{ $turma->nome }}
                                        </option>

                                    @endif

                                @endforeach

                            </select>

                            @error('turma_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="verificar-actions">

                            <button type="submit" formaction="{{ route('admin.usuarios.recusar', $usuario) }}"
                                class="btn btn-outline-danger" data-confirm-rejection>
                                <i class="bi bi-x-circle" aria-hidden="true"></i>
                                Recusar
                            </button>

                            <button type="submit" class="btn btn-success">
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