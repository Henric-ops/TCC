@extends('layout.app')

@section('title', $turma->nome)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">{{ $turma->nome }} <small class="text-muted">— {{ $turma->periodo }}</small></h1>
        <a href="{{ route('turmas.minhas') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
    </div>

    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Idade</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($turma->alunos as $aluno)
                    <tr>
                        <td>{{ $aluno->nome }}</td>
                        <td>{{ $aluno->data_nascimento->age }} anos</td>
                        <td class="text-end">
                            <a href="{{ route('registros.create', ['aluno_id' => $aluno->id]) }}"
                                class="btn btn-sm btn-outline-primary">Registro diário</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Nenhum aluno vinculado a essa turma.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection