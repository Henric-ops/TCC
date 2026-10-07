@extends('layout.app')

@section('title', 'Relatórios')

@section('content')
    <h1 class="h4 mb-4">Relatórios</h1>

    <form method="GET" action="{{ route('admin.relatorios.turma') }}"
        class="card p-3 mb-4 d-flex flex-row flex-wrap gap-3 align-items-end" id="form-relatorio">
        <div>
            <label class="form-label small mb-1">Turma</label>
            <select name="turma_id" id="turma_id" class="form-select form-select-sm" required>
                <option value="">Selecione</option>
                @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}">{{ $turma->nome }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label small mb-1">Aluno (opcional)</label>
            <select name="aluno_id" id="aluno_id" class="form-select form-select-sm">
                <option value="">Todos da turma</option>
                @foreach($alunos as $aluno)
                    <option value="{{ $aluno->id }}" data-turmas="{{ $aluno->turmas->pluck('id')->implode(',') }}">
                        {{ $aluno->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label small mb-1">De</label>
            <input type="date" name="inicio" value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                class="form-control form-control-sm">
        </div>
        <div>
            <label class="form-label small mb-1">Até</label>
            <input type="date" name="fim" value="{{ now()->format('Y-m-d') }}" class="form-control form-control-sm">
        </div>
        <div>
            <button type="submit" class="btn btn-primary btn-sm">Gerar relatório</button>
        </div>
    </form>

    <p class="text-muted small">Escolhe só a turma pra ver o resumo de todos os alunos dela, ou escolhe também um aluno pra
        ver o relatório individual.</p>

    <script>
        const turmaSelect = document.getElementById('turma_id');
        const alunoSelect = document.getElementById('aluno_id');
        const form = document.getElementById('form-relatorio');

        function filtrarAlunos() {
            const turmaId = turmaSelect.value;
            Array.from(alunoSelect.options).forEach((option) => {
                if (!option.value) return;
                const turmas = option.dataset.turmas ? option.dataset.turmas.split(',') : [];
                option.hidden = turmaId !== '' && !turmas.includes(turmaId);
            });
            alunoSelect.value = '';
        }

        turmaSelect.addEventListener('change', filtrarAlunos);

        form.addEventListener('submit', (e) => {
            if (alunoSelect.value) {
                e.preventDefault();
                form.action = "{{ route('admin.relatorios.aluno') }}";
                form.submit();
            }
        });
    </script>
@endsection