@extends('layout.app')

@section('title', 'Editar usuário')

@section('content')
    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
            value="{{ old('nome', $usuario->nome ?? '') }}">
        @error('nome')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $usuario->email ?? '') }}">
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Senha {{ $usuario ? '(deixe em branco pra manter a atual)' : '' }}</label>
        <input type="password" name="senha" class="form-control @error('senha') is-invalid @enderror">
        @error('senha')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Perfil</label>
        <select name="perfil" id="perfil" class="form-select @error('perfil') is-invalid @enderror">
            <option value="">Selecione</option>
            <option value="professor" {{ old('perfil', $usuario->perfil ?? '') === 'professor' ? 'selected' : '' }}>Professor
            </option>
            <option value="responsavel" {{ old('perfil', $usuario->perfil ?? '') === 'responsavel' ? 'selected' : '' }}>
                Responsável</option>
        </select>
        @error('perfil')
        <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div id="bloco-responsavel" class="mb-3" style="display: none;">
        <label class="form-label">Aluno(s) vinculado(s)</label>
        <select name="alunos[]" multiple class="form-select">
            @foreach($alunos as $aluno)
                <option value="{{ $aluno->id }}" {{ in_array($aluno->id, old('alunos', $alunosVinculados)) ? 'selected' : '' }}>
                    {{ $aluno->nome }}
                </option>
            @endforeach
        </select>
        @if($alunos->isEmpty())
            <small class="text-muted">Nenhum aluno cadastrado ainda — cadastre os alunos primeiro pra poder vincular.</small>
        @endif

        <label class="form-label mt-2">Parentesco</label>
        <input type="text" name="parentesco" class="form-control" placeholder="mãe, pai, avó..."
            value="{{ old('parentesco', $usuario->alunosResponsavel->first()->pivot->parentesco ?? '') }}">
    </div>

    <script>
        const perfilSelect = document.getElementById('perfil');
        const blocoResponsavel = document.getElementById('bloco-responsavel');

        function toggleBloco() {
            blocoResponsavel.style.display = perfilSelect.value === 'responsavel' ? 'block' : 'none';
        }

        perfilSelect.addEventListener('change', toggleBloco);
        toggleBloco();
    </script>
@endsection