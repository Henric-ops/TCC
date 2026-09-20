@extends('layout.app')

@section('title', 'Registro Diário - Selecionar turma')

@section('content')
    <h1 class="h4 mb-4">Selecione a turma</h1>

    <div class="card">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Turma</th>
                    <th>Período</th>
                    <th class="text-end">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse($turmas as $turma)
                    <tr>
                        <td>{{ $turma->nome }}</td>
                        <td>{{ $turma->periodo }}</td>
                        <td class="text-end">
                            <a href="{{ route('registros.selecionar-aluno', ['turma_id' => $turma->id]) }}"
                                class="btn btn-sm btn-primary">
                                Ver alunos
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Nenhuma turma disponível.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection