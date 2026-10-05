<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Frequencia;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrequenciaController extends Controller
{
    // Exibe a tela de seleção de turma para professor ou admin
    public function selecionarTurma(Request $request)
    {
        $user = Auth::user();
        $escolaId = $request->query('escola_id');

        $turmasPermitidas = $this->turmasPermitidas($user);

        $turmas = $turmasPermitidas
            ->when(
                $escolaId,
                fn($collection) => $collection->where('escola_id', $escolaId)
            )
            ->values();

        $escolas = $this->escolasPermitidas($user);

        return view(
            'frequencia.selecionar-turma',
            compact('turmas', 'escolas', 'escolaId')
        );
    }

    // Exibe o formulário de frequência
    public function form(Request $request)
    {
        $turma = Turma::find($request->query('turma_id'));

        if (
            !$turma ||
            !$this->turmasPermitidas(Auth::user())->contains('id', $turma->id)
        ) {
            return redirect()
                ->route('frequencia.selecionar')
                ->with('erro', 'Selecione uma turma válida.');
        }

        $data = $request->query('data', now()->format('Y-m-d'));

        $alunos = $turma->alunos()
            ->orderBy('nome')
            ->get();

        $frequenciasExistentes = Frequencia::where('turma_id', $turma->id)
            ->where('data', $data)
            ->get()
            ->keyBy('aluno_id');

        // Se já existem registros para a turma nesta data,
        // o formulário passa a funcionar como edição.
        $modoEdicao = $frequenciasExistentes->isNotEmpty();

        return view(
            'frequencia.form',
            compact(
                'turma',
                'data',
                'alunos',
                'frequenciasExistentes',
                'modoEdicao'
            )
        );
    }

    // Salva uma nova frequência
    public function salvar(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'data' => 'required|date',
            'presenca' => 'required|array',
            'presenca.*' => 'in:presente,falta',
            'justificativa.*' => 'nullable|string|max:255',
        ]);

        $turma = Turma::findOrFail($request->turma_id);

        if (!$this->turmasPermitidas(Auth::user())->contains('id', $turma->id)) {
            abort(403);
        }

        $jaExiste = Frequencia::where('turma_id', $turma->id)
            ->where('data', $request->data)
            ->exists();

        // Impede que a mesma frequência seja lançada novamente.
        if ($jaExiste) {
            return redirect()
                ->route('frequencia.form', [
                    'turma_id' => $turma->id,
                    'data' => $request->data,
                ])
                ->with(
                    'erro',
                    'A frequência desta turma já foi lançada nesta data. Utilize a opção de editar.'
                );
        }

        foreach ($request->presenca as $alunoId => $status) {
            Frequencia::create([
                'aluno_id' => $alunoId,
                'turma_id' => $turma->id,
                'data' => $request->data,
                'presente' => $status === 'presente',
                'justificativa' => $request->input("justificativa.$alunoId"),
                'registrado_por' => Auth::id(),
            ]);
        }

        return redirect()
            ->route('frequencia.form', [
                'turma_id' => $turma->id,
                'data' => $request->data,
            ])
            ->with('sucesso', 'Frequência salva com sucesso.');
    }

    // Atualiza uma frequência que já foi lançada
    public function atualizar(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'data' => 'required|date',
            'presenca' => 'required|array',
            'presenca.*' => 'in:presente,falta',
            'justificativa.*' => 'nullable|string|max:255',
        ]);

        $turma = Turma::findOrFail($request->turma_id);

        if (!$this->turmasPermitidas(Auth::user())->contains('id', $turma->id)) {
            abort(403);
        }

        $frequenciaExiste = Frequencia::where('turma_id', $turma->id)
            ->where('data', $request->data)
            ->exists();

        if (!$frequenciaExiste) {
            return redirect()
                ->route('frequencia.form', [
                    'turma_id' => $turma->id,
                    'data' => $request->data,
                ])
                ->with(
                    'erro',
                    'Não existe frequência lançada para esta turma nesta data.'
                );
        }

        foreach ($request->presenca as $alunoId => $status) {
            Frequencia::updateOrCreate(
                [
                    'aluno_id' => $alunoId,
                    'turma_id' => $turma->id,
                    'data' => $request->data,
                ],
                [
                    'presente' => $status === 'presente',
                    'justificativa' => $request->input("justificativa.$alunoId"),
                    'registrado_por' => Auth::id(),
                ]
            );
        }

        return redirect()
            ->route('frequencia.form', [
                'turma_id' => $turma->id,
                'data' => $request->data,
            ])
            ->with('sucesso', 'Frequência atualizada com sucesso.');
    }

    // Exibe a visão do dia ou o histórico de um aluno específico
    public function index(Request $request)
    {
        $user = Auth::user();

        $escolaId = $request->input('escola_id');
        $alunoId = $request->input('aluno_id');
        $turmaId = $request->input('turma_id');

        $escolasPermitidas = $this->escolasPermitidas($user);
        $turmasTotais = $this->turmasPermitidas($user);

        $turmasPermitidas = $turmasTotais
            ->when(
                $escolaId,
                fn($turmas) => $turmas->where('escola_id', $escolaId)
            )
            ->values();

        if (
            $turmaId &&
            !$turmasPermitidas->contains('id', $turmaId)
        ) {
            $turmaId = null;
        }

        $alunosPermitidos = Aluno::whereIn(
            'id',
            function ($q) use ($turmasTotais) {
                $q->select('aluno_id')
                    ->from('turma_aluno')
                    ->whereIn(
                        'turma_id',
                        $turmasTotais->pluck('id')
                    );
            }
        )
            ->orderBy('nome')
            ->get();

        $turmasPorEscola = $turmasTotais
            ->groupBy('escola_id')
            ->map(
                fn($turmas) =>
                    $turmas->map(
                        fn($turma) => [
                            'id' => $turma->id,
                            'nome' => $turma->nome,
                        ]
                    )
                        ->values()
                        ->all()
            )
            ->all();

        $alunosPorTurma = [];

        foreach ($turmasTotais as $turma) {
            $alunosPorTurma[$turma->id] = $turma->alunos()
                ->orderBy('nome')
                ->get()
                ->map(
                    fn($aluno) => [
                        'id' => $aluno->id,
                        'nome' => $aluno->nome,
                    ]
                )
                ->values()
                ->all();
        }

        if ($turmaId) {
            $alunosParaFiltro = Turma::findOrFail($turmaId)
                ->alunos()
                ->orderBy('nome')
                ->get();
        } else {
            $alunosParaFiltro = $alunosPermitidos;
        }

        if (
            $alunoId &&
            !$alunosParaFiltro->contains('id', $alunoId)
        ) {
            $alunoId = null;
        }

        // Busca o histórico de frequência de um aluno específico
        if ($alunoId) {
            $registros = Frequencia::with('turma')
                ->where('aluno_id', $alunoId)
                ->when(
                    $turmaId,
                    fn($q) => $q->where('turma_id', $turmaId)
                )
                ->when(
                    $request->inicio,
                    fn($q) => $q->where('data', '>=', $request->inicio)
                )
                ->when(
                    $request->fim,
                    fn($q) => $q->where('data', '<=', $request->fim)
                )
                ->orderByDesc('data')
                ->paginate(20);

            return view(
                'frequencia.index',
                compact(
                    'registros',
                    'alunosParaFiltro',
                    'turmasPermitidas',
                    'escolasPermitidas',
                    'alunoId',
                    'turmaId',
                    'escolaId',
                    'turmasPorEscola',
                    'alunosPorTurma',
                    'turmasTotais'
                )
            );
        }

        // Busca o resumo de frequência do dia
        $data = $request->input(
            'data',
            now()->format('Y-m-d')
        );

        $query = Frequencia::where('data', $data);

        if ($turmaId) {
            $query->where('turma_id', $turmaId);
        } else {
            $query->whereIn(
                'turma_id',
                $turmasPermitidas->pluck('id')
            );
        }

        $resumo = $query
            ->selectRaw(
                'turma_id, data, COUNT(*) as total, SUM(presente) as presentes'
            )
            ->groupBy('turma_id', 'data')
            ->with([
                'turma' => fn($q) => $q->withCount('alunos')
            ])
            ->get();

        return view(
            'frequencia.index',
            compact(
                'resumo',
                'alunosParaFiltro',
                'turmasPermitidas',
                'escolasPermitidas',
                'alunoId',
                'turmaId',
                'escolaId',
                'data',
                'turmasPorEscola',
                'alunosPorTurma',
                'turmasTotais'
            )
        );
    }

    public function meusRegistros(Request $request)
    {
        $alunoIds = Auth::user()
            ->alunosResponsavel()
            ->withTrashed()
            ->pluck('alunos.id');

        $filhos = Auth::user()
            ->alunosResponsavel()
            ->withTrashed()
            ->get();

        $temFiltroData =
            $request->filled('inicio') ||
            $request->filled('fim');

        $frequencias = Frequencia::with([
            'aluno',
            'turma'
        ])
            ->whereIn('aluno_id', $alunoIds)
            ->when(
                $request->filled('aluno_id'),
                fn($q) =>
                    $q->where(
                        'aluno_id',
                        $request->aluno_id
                    )
            )
            ->when(
                $temFiltroData,
                function ($q) use ($request) {
                    $q->when(
                        $request->filled('inicio'),
                        fn($q2) =>
                            $q2->where(
                                'data',
                                '>=',
                                $request->inicio
                            )
                    )
                        ->when(
                            $request->filled('fim'),
                            fn($q2) =>
                                $q2->where(
                                    'data',
                                    '<=',
                                    $request->fim
                                )
                        );
                },
                function ($q) {
                    $q->whereDate(
                        'data',
                        now()->format('Y-m-d')
                    );
                }
            )
            ->latest('data')
            ->paginate(20);

        return view(
            'frequencia.frequencia-responsavel',
            compact(
                'frequencias',
                'filhos',
                'temFiltroData'
            )
        );
    }

    private function turmasPermitidas($user)
    {
        if ($user->perfil === 'admin') {
            return Turma::orderBy('nome')->get();
        }

        return $user->turmas()
            ->orderBy('nome')
            ->get();
    }

    private function escolasPermitidas($user)
    {
        if ($user->perfil === 'admin') {
            return Escola::orderBy('nome')->get();
        }

        $escolaIds = $this->turmasPermitidas($user)
            ->pluck('escola_id')
            ->filter()
            ->unique();

        return Escola::whereIn('id', $escolaIds)
            ->orderBy('nome')
            ->get();
    }
}