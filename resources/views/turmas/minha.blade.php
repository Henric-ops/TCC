@extends('layout.app')

@section('title', $turma->nome)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/turmas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="turmas-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $turma->nome }}</h1>

        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="turmas-total">

            </span>
            <a href="{{ route('turmas.minhas') }}" class="usuario-button usuario-button-muted">Voltar</a>
        </div>
    </div>

    <div class="card turmas-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                            <td><strong>{{ $aluno->nome }}</strong></td>
                            <td>{{ $aluno->data_nascimento->age }} anos</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('registros.create', ['aluno_id' => $aluno->id]) }}"
                                    class="usuario-button usuario-button-primary" title="Registrar" aria-label="Registrar">
                                    <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                </a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="turma-empty">
                                    <i class="bi bi-person-x" aria-hidden="true"></i>
                                    <span>Nenhum aluno vinculado a essa turma.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection