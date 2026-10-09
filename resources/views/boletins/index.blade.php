@extends('layout.app')

@section('title', 'Boletins')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/boletins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historico-frequencia.css') }}">
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

    <form method="GET" class="freq-filtros">
        <div class="freq-filtro-item">
            <label for="busca">Aluno</label>
            <input type="text" id="busca" name="busca" value="{{ $busca }}" placeholder="Buscar por nome...">
        </div>

        <div class="freq-filtro-item">
            <label for="turma_id">Turma</label>
            <select name="turma_id" id="turma_id">
                <option value="">Todas as turmas</option>
                @foreach ($turmasPermitidas as $turma)
                    <option value="{{ $turma->id }}" {{ (string) $turmaId === (string) $turma->id ? 'selected' : '' }}>
                        {{ $turma->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="usuario-button usuario-button-primary">
            <i class="bi bi-search" aria-hidden="true"></i>
            Buscar
        </button>

        <a href="{{ route('boletins.index') }}" class="usuario-button usuario-button-muted">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
            Limpar
        </a>
    </form>

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
                            <td>
                                <strong>
                                    {{ $boletim->aluno?->nome ?? 'Aluno não disponível' }}
                                </strong>
                                @if ($boletim->aluno?->trashed())
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">Desligado</span>
                                @endif
                            </td>
                            <td>
                                @if ($boletim->observacao && ($boletim->documento || $boletim->arquivo_pdf))
                                    <span class="badge bg-success-subtle text-success-emphasis">
                                        Avaliação + PDF
                                    </span>
                                @elseif ($boletim->observacao)
                                    <span class="badge bg-primary-subtle text-primary-emphasis">
                                        Avaliação
                                    </span>
                                @elseif ($boletim->documento || $boletim->arquivo_pdf)
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
                                    <span>Ver</span>
                                </a>
                                <form method="POST" action="{{ route('boletins.destroy', $boletim) }}" class="d-inline"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este boletim?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="usuario-action usuario-action-danger" title="Excluir boletim"
                                        aria-label="Excluir boletim">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                        <span>Excluir</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="boletim-empty">
                                    <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                    <span>Nenhum boletim cadastrado ainda.</span>

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