@extends('layout.app')

@section('title', 'Registros Diários')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/registro-diario.css') }}">
    <div class="registros-header d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div>
                <h1 class="h4 mb-1">Registros Diários</h1>
            </div>
        </div>

        <div class="registros-header-actions d-flex align-items-center gap-3">
            <span class="registros-total">
                <strong>{{ $registros->total() }}</strong>
                {{ $registros->total() === 1 ? 'registro' : 'registros' }}
            </span>
            <a href="{{ route('registros.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                Novo registro
            </a>
        </div>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            {{ session('sucesso') }}
        </div>
    @endif

    <div class="card registros-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Aluno</th>
                        <th><i class="bi bi-calendar3" aria-hidden="true"></i> Data</th>
                        <th><i class="bi bi-person-badge" aria-hidden="true"></i> Professor</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $registro)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="registro-avatar" aria-hidden="true">
                                        {{ strtoupper(substr($registro->aluno->nome, 0, 1)) }}
                                    </span>
                                    <strong>{{ $registro->aluno->nome }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="registro-date">
                                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                                    {{ $registro->data->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="registro-professor">
                                    <i class="bi bi-person-badge" aria-hidden="true"></i>
                                    {{ $registro->professor->nome }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('registros.edit', $registro) }}"
                                    class="btn btn-sm btn-primary registro-action" title="Editar registro"
                                    aria-label="Editar registro">
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                    <span>Editar</span>
                                </a>
                                <form action="{{ route('registros.destroy', $registro) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Remover este registro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger registro-action" title="Excluir registro"
                                        aria-label="Excluir registro">
                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                        <span>Excluir</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="registro-empty">
                                    <i class="bi bi-inbox" aria-hidden="true"></i>
                                    <span>Nenhum registro cadastrado ainda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $registros->links() }}
    </div>
@endsection