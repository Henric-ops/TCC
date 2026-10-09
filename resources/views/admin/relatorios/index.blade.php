@extends('layout.app')

@section('title', 'Relatórios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-relatorios.css') }}">
@endpush

@section('content')
    <main class="admin-reports">
        <h1 class="admin-reports__title">Relatórios</h1>

        <form method="GET" action="{{ route('admin.relatorios.turma.pdf') }}" class="admin-reports__filters"
            id="form-relatorio">
            <div class="admin-reports__field">
                <label for="escola_id">Escola</label>
                <select name="escola_id" id="escola_id" class="form-select">
                    <option value="">Todas as escolas</option>
                    @foreach($escolas as $escola)
                        <option value="{{ $escola->id }}">{{ $escola->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="admin-reports__field">
                <label for="turma_id">Turma</label>
                <select name="turma_id" id="turma_id" class="form-select" required>
                    <option value="">Selecione uma turma</option>
                    @foreach($turmas as $turma)
                        <option value="{{ $turma->id }}" data-escola="{{ $turma->escola_id }}">{{ $turma->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="admin-reports__field">
                <label for="aluno_id">Aluno</label>
                <select name="aluno_id" id="aluno_id" class="form-select">
                    <option value="">Todos da turma</option>
                    @foreach($alunos as $aluno)
                        <option value="{{ $aluno->id }}" data-escola="{{ $aluno->escola_id }}"
                            data-turmas="{{ $aluno->turmas->pluck('id')->implode(',') }}">
                            {{ $aluno->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-reports__field">
                <label for="inicio">De</label>
                <input id="inicio" type="date" name="inicio" value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                    class="form-control">
            </div>
            <div class="admin-reports__field">
                <label for="fim">Até</label>
                <input id="fim" type="date" name="fim" value="{{ now()->format('Y-m-d') }}" class="form-control">
            </div>
            <div class="admin-reports__actions">
                <button type="submit" formaction="{{ route('admin.relatorios.turma.pdf') }}"
                    class="usuario-button usuario-button-primary">
                    <i class="bi bi-download" aria-hidden="true"></i> Baixar PDF da turma
                </button>
                <button type="submit" formaction="{{ route('admin.relatorios.aluno.pdf') }}"
                    class="usuario-button usuario-button-primary" id="baixar-pdf-aluno" disabled>
                    <i class="bi bi-download" aria-hidden="true"></i> Baixar PDF do aluno
                </button>
            </div>
        </form>
    </main>

    <script>
        const turmaSelect = document.getElementById('turma_id');
        const alunoSelect = document.getElementById('aluno_id');
        const baixarPdfAluno = document.getElementById('baixar-pdf-aluno');
        const escolaSelect = document.getElementById('escola_id');

        function filtrarAlunos() {
            const turmaId = turmaSelect.value;
            const escolaId = escolaSelect.value;
            Array.from(alunoSelect.options).forEach((option) => {
                if (!option.value) return;
                const turmas = option.dataset.turmas ? option.dataset.turmas.split(',') : [];
                const escolaIncompativel = escolaId !== '' && option.dataset.escola !== escolaId;
                const turmaIncompativel = turmaId === '' || !turmas.includes(turmaId);
                option.hidden = escolaIncompativel || turmaIncompativel;
            });
        }

        function atualizarAcaoAluno() {
            baixarPdfAluno.disabled = turmaSelect.value === '' || alunoSelect.value === '';
        }

        function filtrarTurmas() {
            const escolaId = escolaSelect.value;
            Array.from(turmaSelect.options).forEach((option) => {
                if (!option.value) return;
                option.hidden = escolaId !== '' && option.dataset.escola !== escolaId;
            });
            turmaSelect.value = '';
            alunoSelect.value = '';
            filtrarAlunos();
            atualizarAcaoAluno();
        }

        escolaSelect.addEventListener('change', filtrarTurmas);
        turmaSelect.addEventListener('change', () => {
            alunoSelect.value = '';
            filtrarAlunos();
            atualizarAcaoAluno();
        });
        alunoSelect.addEventListener('change', atualizarAcaoAluno);
        filtrarTurmas();
    </script>
@endsection