@extends('layout.app')

@section('title', 'Editar aluno')

@section('content')
    <h1 class="h4 mb-4">Editar aluno</h1>

    <form action="{{ route('admin.alunos.update', $aluno) }}" method="POST" enctype="multipart/form-data" class="card p-4" style="max-width: 600px;">
        @csrf
        @method('PUT')

        @if($aluno->foto)
            <div class="mb-3">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($aluno->foto) }}" alt="{{ $aluno->nome }}"
                    class="rounded-circle" style="width:64px;height:64px;object-fit:cover;">
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                value="{{ old('nome', $aluno->nome) }}">
            @error('nome')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Data de nascimento</label>
            <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror"
                value="{{ old('data_nascimento', $aluno->data_nascimento->format('Y-m-d')) }}">
            @error('data_nascimento')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Foto {{ $aluno->foto ? '(deixe em branco pra manter a atual)' : '' }}</label>
            <input type="file" name="foto" accept="image/*" class="form-control @error('foto') is-invalid @enderror">
            @error('foto')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
    <label class="form-label">Escola</label>
    <select name="escola_id" class="form-select @error('escola_id') is-invalid @enderror">
        <option value="">Selecione</option>
        @foreach($escolas as $escola)
            <option value="{{ $escola->id }}" {{ old('escola_id', $aluno->escola_id) == $escola->id ? 'selected' : '' }}>
                {{ $escola->nome }}
            </option>
        @endforeach
    </select>
    @error('escola_id')
    <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

        <div class="mb-3">
            <label class="form-label">Turma(s)</label>
            <select name="turmas[]" multiple class="form-select">
                @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}" {{ in_array($turma->id, old('turmas', $turmasVinculadas)) ? 'selected' : '' }}>
                        {{ $turma->nome }} — {{ $turma->periodo }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Atualizar</button>
    </form>
@endsection