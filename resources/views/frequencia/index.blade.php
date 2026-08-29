@extends('layout.app')

@section('title', 'Histórico de frequência')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/frequencia.css') }}">
@endpush

@section('content')
    <div class="frequencia-header">
        <h1>Histórico de frequência</h1>

        <div class="frequencia-header-actions">
            <span class="frequencia-total">
                <strong>{{ $frequencias->total() }}</strong>
                {{ $frequencias->total() === 1 ? 'registro' : 'registros' }}
            </span>
        </div>
    </div>

    <div class="card frequencia-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th><i class="bi bi-person" aria-hidden="true"></i> Aluno</th>
                        <th><i class="bi bi-people" aria-hidden="true"></i> Turma</th>
                        <th><i class="bi bi-calendar3" aria-hidden="true"></i> Data</th>
                        <th><i class="bi bi-check2-circle" aria-hidden="true"></i> Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($frequencias as $freq)
                        <tr>
                            <td><strong>{{ $freq->aluno->nome }}</strong></td>
                            <td>{{ $freq->turma->nome }}</td>
                            <td>{{ $freq->data->format('d/m/Y') }}</td>
                            <td>
                                @if($freq->presente)
                                    <span class="freq-badge freq-badge--presente">
                                        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                        Presente
                                    </span>
                                @else
                                    <span class="freq-badge freq-badge--falta">
                                        <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                                        Falta
                                    </span>
                                    @if($freq->justificativa)
                                        <small class="freq-justificativa">{{ $freq->justificativa }}</small>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="frequencia-empty">
                                    <i class="bi bi-calendar2-x" aria-hidden="true"></i>
                                    <span>Nenhum registro ainda.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $frequencias->links() }}</div>
@endsection