@extends('layout.app')

@section('title', 'Registros dos meus filhos')

@section('content')
    <h1 class="h4 mb-4">Registros diários</h1>

    <div class="card">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Data</th>
                    <th>Professor</th>
                    <th class="text-end">Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros as $registro)
                    <tr>
                        <td>{{ $registro->aluno->nome }}</td>
                        <td>{{ $registro->data->format('d/m/Y') }}</td>
                        <td>{{ $registro->professor->nome }}</td>
                        <td class="text-end">
                            <a href="{{ route('registros.meu-detalhe', $registro) }}" class="btn btn-sm btn-outline-primary">Ver
                                detalhes</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Nenhum registro ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $registros->links() }}</div>
@endsection