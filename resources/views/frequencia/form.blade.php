@extends('layout.app')

@section('title', 'Marcar frequência')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/frequencia.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="frequencia-header">
        <div class="frequencia-title">
            <div>
                <h1>Frequência</h1>
                <p>{{ $turma->nome }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('frequencia.form') }}" class="frequencia-date-form">
            <input type="hidden" name="turma_id" value="{{ $turma->id }}">
            <label for="data-frequencia">Data</label>
            <input type="date" name="data" value="{{ $data }}" class="form-control form-control-sm" id="data-frequencia"
                onchange="this.form.submit()">
        </form>
    </div>

    @if(session('sucesso'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            {{ session('sucesso') }}
        </div>
    @endif

    <form method="POST" action="{{ route('frequencia.salvar') }}" class="frequencia-card">
        @csrf
        <input type="hidden" name="turma_id" value="{{ $turma->id }}">
        <input type="hidden" name="data" value="{{ $data }}">

        <div class="freq-table-header" aria-hidden="true">
            <span>Aluno</span>
            <div class="freq-status-legend">
                <span class="freq-status-legend-item freq-status-legend-presente">
                    Presente
                </span>
                <span class="freq-status-legend-item freq-status-legend-falta">
                    Falta
                </span>
            </div>
        </div>

        @forelse($alunos as $aluno)
            @php $existente = $frequenciasExistentes->get($aluno->id); @endphp
            <div class="freq-row">
                <div class="freq-student">
                    <span class="freq-student-name">{{ $aluno->nome }}</span>
                    <div class="freq-toggle-group" role="group" aria-label="Presença de {{ $aluno->nome }}">
                        <input type="radio" class="btn-check" name="presenca[{{ $aluno->id }}]" id="presente-{{ $aluno->id }}"
                            value="presente" {{ (!$existente || $existente->presente) ? 'checked' : '' }}>
                        <label class="btn btn-sm freq-option freq-option-presente" for="presente-{{ $aluno->id }}"
                            aria-label="Presente" title="Presente">
                            <i class="bi bi-check-circle" aria-hidden="true"></i>
                        </label>

                        <input type="radio" class="btn-check" name="presenca[{{ $aluno->id }}]" id="falta-{{ $aluno->id }}"
                            value="falta" {{ ($existente && !$existente->presente) ? 'checked' : '' }}>
                        <label class="btn btn-sm freq-option freq-option-falta" for="falta-{{ $aluno->id }}" aria-label="Falta"
                            title="Falta">
                            <i class="bi bi-x-circle" aria-hidden="true"></i>
                        </label>
                    </div>
                </div>
                <input type="text" name="justificativa[{{ $aluno->id }}]" class="form-control justificativa-input"
                    placeholder="Justificativa da falta (opcional)" value="{{ $existente->justificativa ?? '' }}">
            </div>
        @empty
            <div class="frequencia-empty">
                <i class="bi bi-people" aria-hidden="true"></i>
                <span>Essa turma ainda não tem alunos vinculados.</span>
            </div>
        @endforelse

        @if($alunos->isNotEmpty())
            <div class="freq-form-actions">
                <button type="button" class="usuario-button usuario-button-muted" onclick="window.history.back()">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Voltar
                </button>

                <button type="submit" class="usuario-button usuario-button-primary">
                    <i class="bi bi-floppy" aria-hidden="true"></i>
                    Salvar
                </button>
            </div>
        @endif
    </form>

    <script>
        document.querySelectorAll('.freq-row').forEach((row) => {
            const radios = row.querySelectorAll('input[type=radio]');
            const justInput = row.querySelector('.justificativa-input');

            function atualizar() {
                const selecionado = row.querySelector('input[type=radio]:checked');
                if (!justInput) return;
                justInput.style.display = selecionado && selecionado.value === 'falta' ? 'block' : 'none';
            }

            radios.forEach((r) => r.addEventListener('change', atualizar));
            atualizar();
        });
    </script>
@endsection