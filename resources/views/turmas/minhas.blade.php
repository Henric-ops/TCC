@extends('layout.app')

@section('title', 'Minhas Turmas')

@section('content')
    <h1 class="h4 mb-4">Minhas Turmas</h1>

    <div class="card">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Ano</th>
                    <th>Período</th>
                    <th>Alunos</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($turmas as $turma)
                    <tr>
                        <td>{{ $turma->nome }}</td>
                        <td>{{ $turma->ano }}</td>
                        <td>{{ $turma->periodo }}</td>
                        <td>{{ $turma->alunos_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('turmas.minha', $turma) }}" class="btn btn-sm btn-outline-primary">Ver alunos</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Você ainda não está vinculado a nenhuma turma.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection