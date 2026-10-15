@extends('layout.app')

@section('title', 'Meus boletins')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/boletins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')

    <div class="boletins-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">Meus boletins</h1>
        </div>
    </div>

    <div class="card boletins-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Período</th>
                        <th>Conteúdo</th>
                        <th>Data</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($boletins as $boletim)
                        <tr class="boletim-row">
                            <td data-label="Aluno">
                                <strong>{{ $boletim->aluno->nome }}</strong>
                                @if ($boletim->aluno->trashed())
                                    <small class="text-muted ms-1">Desligado</small>
                                @endif
                            </td>
                            <td data-label="Período">{{ $boletim->periodo }}</td>
                            <td data-label="Conteúdo">
                                @if ($boletim->observacao && ($boletim->documento || $boletim->arquivo_pdf))
                                    Avaliação + PDF
                                @elseif ($boletim->observacao)
                                    Avaliação
                                @elseif ($boletim->documento || $boletim->arquivo_pdf)
                                    PDF
                                @endif
                            </td>
                            <td data-label="Data">{{ $boletim->created_at->format('d/m/Y') }}</td>
                            <td data-label="Ação" class="text-end text-nowrap">
                                <a href="{{ route('boletins.meu-detalhe', $boletim) }}"
                                    class="usuario-action usuario-action-primary" title="Visualizar boletim"
                                    aria-label="Visualizar boletim">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                    <span>Visualizar</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="boletim-empty-row">
                            <td colspan="5" class="text-center py-5">
                                <div class="boletim-empty">
                                    <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                    <span>Ainda não existem boletins disponíveis para seus filhos.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $boletins->links() }}
    </div>

@endsection