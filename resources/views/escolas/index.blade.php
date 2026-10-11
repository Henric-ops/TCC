@extends('layout.app')

@section('title', 'Escolas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/escolas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="escolas-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-1">Escolas</h1>

        <div class="escolas-header-actions d-flex align-items-center gap-3">
            <a href="{{ route('admin.escolas.create') }}" class="usuario-button usuario-button-primary">
                <i class="bi bi-building-add" aria-hidden="true"></i>
                Nova escola
            </a>
        </div>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            {{ session('sucesso') }}
        </div>
    @endif

    @if(session('erro'))
        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            {{ session('erro') }}
        </div>
    @endif

    <div class="card escolas-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CNPJ</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($escolas as $escola)
                        <tr>
                            <td>
                                <strong>{{ $escola->nome }}</strong>
                            </td>
                            <td>{{ $escola->cnpj }}</td>
                            <td>{{ $escola->telefone }}</td>
                            <td>{{ $escola->email }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.escolas.edit', $escola) }}"
                                    class="usuario-action usuario-action-primary" title="Editar escola"
                                    aria-label="Editar escola">
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('admin.escolas.destroy', $escola) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Remover esta escola?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="usuario-action usuario-action-danger" title="Excluir escola"
                                        aria-label="Excluir escola">
                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="escola-empty">
                                    <i class="bi bi-building" aria-hidden="true"></i>
                                    <span>Nenhuma escola cadastrada ainda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $escolas->links() }}
    </div>
@endsection