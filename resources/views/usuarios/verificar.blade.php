@extends('layout.app')

@section('title', 'Verificar cadastro')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h4 mb-1">Verificar cadastro</h1>
                <p class="text-muted mb-0">
                    Analise os dados antes de aprovar o usuário.
                </p>
            </div>

            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                Voltar
            </a>
        </div>

        <div class="card">

            <div class="card-header">
                <strong>Dados do usuário</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome</label>

                        <input type="text" class="form-control" value="{{ $usuario->nome }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">E-mail</label>

                        <input type="email" class="form-control" value="{{ $usuario->email }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Escola</label>

                        <input type="text" class="form-control" value="{{ $usuario->escola->nome }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Perfil</label>

                        <input type="text" class="form-control text-capitalize" value="{{ $usuario->perfil }}" disabled>
                    </div>

                </div>

                <hr>


                @if($usuario->perfil === 'responsavel')

                    <h5 class="mb-3">
                        Vínculo do responsável
                    </h5>

                    <form method="POST" action="{{ route('admin.usuarios.aprovar', $usuario) }}">

                        @csrf

                        <div class="mb-3">

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

                        <div class="mb-3">

                            <label class="form-label">
                                Parentesco
                            </label>

                            <input type="text" name="parentesco" class="form-control @error('parentesco') is-invalid @enderror"
                                value="{{ old('parentesco') }}" placeholder="Ex.: mãe, pai, avó..." required>

                            @error('parentesco')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <button type="submit" formaction="{{ route('admin.usuarios.recusar', $usuario) }}"
                                class="btn btn-outline-danger">
                                Recusar
                            </button>

                            <button type="submit" class="btn btn-success">
                                Aprovar cadastro
                            </button>

                        </div>

                    </form>

                    {{-- PROFESSOR --}}
                @elseif($usuario->perfil === 'professor')

                    <h5 class="mb-3">
                        Vínculo do professor
                    </h5>

                    <form method="POST" action="{{ route('admin.usuarios.aprovar', $usuario) }}">

                        @csrf

                        <div class="mb-3">

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

                        <div class="d-flex justify-content-end gap-2">

                            <button type="submit" formaction="{{ route('admin.usuarios.recusar', $usuario) }}"
                                class="btn btn-outline-danger">
                                Recusar
                            </button>

                            <button type="submit" class="btn btn-success">
                                Aprovar cadastro
                            </button>

                        </div>

                    </form>

                @endif

            </div>

        </div>

    </div>

@endsection