@extends('layout.app')

@section('title', 'Registros dos meus filhos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/registro-diario.css') }}">
@endpush

@section('content')
    <div class="registros-header d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div>
                <h1 class="h4 mb-1">Registros diários</h1>
            </div>
        </div>

        <div class="registros-header-actions d-flex align-items-center gap-3">

        </div>
    </div>

    <div class="card registros-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Aluno</th>
                        <th><i class="bi bi-calendar3" aria-hidden="true"></i> Data</th>
                        <th><i class="bi bi-person-badge" aria-hidden="true"></i> Professor</th>
                        <th class="text-end">Ação</th>
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
                                <a href="{{ route('registros.meu-detalhe', $registro) }}"
                                    class="btn btn-sm btn-primary registro-action" title="Ver detalhes"
                                    aria-label="Ver detalhes">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                    <span>Ver detalhes</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="registro-empty">
                                    <i class="bi bi-inbox" aria-hidden="true"></i>
                                    <span>Nenhum registro ainda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $registros->links() }}</div>
@endsection