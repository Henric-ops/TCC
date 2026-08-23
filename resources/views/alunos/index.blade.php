@extends('layout.app')

@section('title', 'Alunos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/alunos.css') }}">
@endpush

@section('content')
    <div class="alunos-header">
        <h1 class="alunos-title">Alunos</h1>
        <a href="{{ route('admin.alunos.create') }}" class="btn-novo">
            <i class="bi bi-plus-lg"></i> Novo aluno
        </a>
    </div>

    @if(session('sucesso'))
        <div class="alunos-alert">{{ session('sucesso') }}</div>
    @endif

    <div class="alunos-panel">
        @if($alunos->isEmpty())
            <div class="alunos-empty">
                <i class="bi bi-person"></i>
                Nenhum aluno cadastrado ainda.
            </div>
        @else
            <table class="alunos-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Nome</th>
                        <th><i class="bi bi-calendar3" aria-hidden="true"></i> Idade</th>
                        <th><i class="bi bi-people" aria-hidden="true"></i> Turmas</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alunos as $aluno)
                        <tr>

                            <td class="aluno-nome">
                                <span class="aluno-identidade">
                                    <i class="bi bi-person-circle" aria-hidden="true"></i>
                                    {{ $aluno->nome }}
                                </span>
                            </td>
                            <td><span class="badge-idade">{{ $aluno->data_nascimento->age }} anos</span></td>
                            <td><span class="badge-turmas">{{ $aluno->turmas->pluck('nome')->join(', ') }}</span></td>
                            <td class="acoes-cell">
                                <a href="{{ route('admin.alunos.edit', $aluno) }}" class="btn-editar">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('admin.alunos.destroy', $aluno) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Remover este aluno?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-excluir">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-3">
        {{ $alunos->links() }}
    </div>
@endsection