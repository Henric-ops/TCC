@extends('layout.app')

@section('title', 'Registro Diário - Selecionar aluno')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">{{ $turma->nome }} <small class="text-muted">— selecione o aluno</small></h1>
        <a href="{{ route('registros.selecionar-turma') }}" class="btn btn-sm btn-outline-secondary">Trocar turma</a>
    </div>

    <div class="card">
        <table class="table table-hover mb-0">
            <tbody>
                @forelse($alunos as $aluno)
                    <tr>
                        <td>{{ $aluno->nome }}</td>
                        <td class="text-end">
                            <a href="{{ route('registros.create', ['aluno_id' => $aluno->id]) }}"
                                class="btn btn-sm btn-primary">
                                Fazer registro
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-muted py-4">Nenhum aluno vinculado a essa turma.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection