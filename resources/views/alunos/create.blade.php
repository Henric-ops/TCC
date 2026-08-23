@extends('layout.app')

@section('title', 'Novo aluno')

@section('content')
    <h1 class="h4 mb-4">Novo aluno</h1>

    <form action="{{ route('admin.alunos.store') }}" method="POST" enctype="multipart/form-data" class="card p-4"
        style="max-width: 600px;">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                value="{{ old('nome') }}">
            @error('nome')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Data de nascimento</label>
            <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror"
                value="{{ old('data_nascimento') }}">
            @error('data_nascimento')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Escola</label>
            <select name="escola_id" class="form-select @error('escola_id') is-invalid @enderror">
                <option value="">Selecione</option>
                @foreach($escolas as $escola)
                    <option value="{{ $escola->id }}" {{ old('escola_id') == $escola->id ? 'selected' : '' }}>
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
                    <option value="{{ $turma->id }}" {{ in_array($turma->id, old('turmas', [])) ? 'selected' : '' }}>
                        {{ $turma->nome }} — {{ $turma->periodo }}
                    </option>
                @endforeach
            </select>
            @if($turmas->isEmpty())
                <small class="text-muted">Nenhuma turma cadastrada ainda.</small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary mt-2">Salvar</button>
    </form>
@endsection