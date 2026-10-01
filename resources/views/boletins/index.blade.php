@extends('layout.app')

@section('title', 'Boletins')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/boletins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')

    <div class="boletins-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">Boletins</h1>
        </div>

        <div class="boletins-header-actions d-flex align-items-center gap-3">
            <a href="{{ route('boletins.create') }}" class="usuario-button usuario-button-primary">
                <i class="bi bi-file-earmark-plus" aria-hidden="true"></i>
                Novo boletim
            </a>
        </div>
    </div>

    @if (session('sucesso'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            <span>
                {{ session('sucesso') }}
            </span>
        </div>
    @endif

    <div class="card boletins-table-card">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>
                            <i class="bi bi-person" aria-hidden="true"></i>
                            Aluno
                        </th>
                        <th>
                            <i class="bi bi-calendar3" aria-hidden="true"></i>
                            Período
                        </th>
                        <th>
                            <i class="bi bi-person-badge" aria-hidden="true"></i>
                            Responsável pelo registro
                        </th>
                        <th>
                            <i class="bi bi-file-earmark-check" aria-hidden="true"></i>
                            Conteúdo
                        </th>
                        <th>
                            <i class="bi bi-clock" aria-hidden="true"></i>
                            Data
                        </th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($boletins as $boletim)
                        <tr>
                            <td><strong>{{ $boletim->aluno->nome }}</strong></td>
                            <td>{{ $boletim->periodo }}</td>
                            <td>{{ $boletim->usuario->nome }}</td>
                            <td>
                                @if ($boletim->observacao && $boletim->arquivo_pdf)
                                    <span class="badge bg-success-subtle text-success-emphasis">
                                        Avaliação + PDF
                                    </span>
                                @elseif ($boletim->observacao)
                                    <span class="badge bg-primary-subtle text-primary-emphasis">
                                        Avaliação
                                    </span>
                                @elseif ($boletim->arquivo_pdf)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                        PDF
                                    </span>
                                @endif
                            </td>
                            <td>{{ $boletim->created_at->format('d/m/Y') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('boletins.show', $boletim) }}" class="usuario-action usuario-action-primary"
                                    title="Visualizar boletim" aria-label="Visualizar boletim">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                    <span>Visualizar</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="boletim-empty">
                                    <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                    <span>Nenhum boletim cadastrado ainda.</span>
                                    <a href="{{ route('boletins.create') }}" class="usuario-button usuario-button-primary mt-3">
                                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                                        Criar primeiro boletim
                                    </a>
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