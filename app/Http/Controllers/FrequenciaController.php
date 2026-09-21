<?php

namespace App\Http\Controllers;

use App\Models\Frequencia;
use App\Models\Turma;
use Illuminate\Http\Request;
use App\Models\Aluno;


class FrequenciaController extends Controller
{
    public function selecionarTurma()
    {
        $turmas = $this->turmasPermitidas(auth()->user());

        return view('frequencia.selecionar-turma', compact('turmas'));
    }

    public function form(Request $request)
    {
        $turma = Turma::find($request->query('turma_id'));

        if (!$turma || !$this->turmasPermitidas(auth()->user())->contains('id', $turma->id)) {
            return redirect()->route('frequencia.selecionar')->with('erro', 'Selecione uma turma válida.');
        }

        $data = $request->query('data', now()->format('Y-m-d'));

        $alunos = $turma->alunos()->orderBy('nome')->get();

        $frequenciasExistentes = Frequencia::where('turma_id', $turma->id)
            ->where('data', $data)
            ->get()
            ->keyBy('aluno_id');

        return view('frequencia.form', compact('turma', 'data', 'alunos', 'frequenciasExistentes'));
    }

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

        if (!$this->turmasPermitidas(auth()->user())->contains('id', $turma->id)) {
            abort(403);
        }

        foreach ($request->presenca as $alunoId => $status) {
            Frequencia::updateOrCreate(
                ['aluno_id' => $alunoId, 'turma_id' => $turma->id, 'data' => $request->data],
                [
                    'presente' => $status === 'presente',
                    'justificativa' => $request->input("justificativa.$alunoId"),
                    'registrado_por' => auth()->id(),
                ]
            );
        }

        return redirect()->route('frequencia.form', ['turma_id' => $turma->id, 'data' => $request->data])
            ->with('sucesso', 'Frequência salva com sucesso.');
    }



    public function index(Request $request)//método para exibir a visão do dia ou o histórico de um aluno específico
    {
        $user = auth()->user();
        $turmasPermitidas = $this->turmasPermitidas($user);

        $alunoId = $request->input('aluno_id');
        $turmaId = $request->input('turma_id');

        $alunosPermitidos = Aluno::whereIn('id', function ($q) use ($turmasPermitidas) {
            $q->select('aluno_id')->from('turma_aluno')->whereIn('turma_id', $turmasPermitidas->pluck('id'));
        })->orderBy('nome')->get();

        if ($turmaId) {
            abort_unless($turmasPermitidas->contains('id', $turmaId), 403);

            $alunosParaFiltro = Turma::findOrFail($turmaId)
                ->alunos()
                ->orderBy('nome')
                ->get();
        } else {
            $alunosParaFiltro = $alunosPermitidos;
        }

        //busca o histórico de frequência de um aluno específico
        if ($alunoId) {
            abort_unless($alunosParaFiltro->contains('id', $alunoId), 403);

            $registros = Frequencia::with('turma')
                ->where('aluno_id', $alunoId)
                ->when($turmaId, fn($q) => $q->where('turma_id', $turmaId))
                ->when($request->inicio, fn($q) => $q->where('data', '>=', $request->inicio))
                ->when($request->fim, fn($q) => $q->where('data', '<=', $request->fim))
                ->orderByDesc('data')
                ->paginate(20);

            return view('frequencia.index', compact(
                'registros',
                'alunosParaFiltro',
                'turmasPermitidas',
                'alunoId',
                'turmaId'
            ));
        }

        // busca o resumo de frequência do dia ou de uma turma específica
        $data = $request->input('data', now()->format('Y-m-d'));

        $query = Frequencia::where('data', $data);

        if ($turmaId) {
            $query->where('turma_id', $turmaId);
        } else {
            $query->whereIn('turma_id', $turmasPermitidas->pluck('id'));
        }

        $resumo = $query->selectRaw('turma_id, data, COUNT(*) as total, SUM(presente) as presentes')
            ->groupBy('turma_id', 'data')
            ->with(['turma' => fn($q) => $q->withCount('alunos')])
            ->get();

        return view('frequencia.index', compact(
            'resumo',
            'alunosParaFiltro',
            'turmasPermitidas',
            'alunoId',
            'turmaId',
            'data'
        ));
    }

    public function meusRegistros(Request $request)
    {
        $alunoIds = auth()->user()->alunosResponsavel->pluck('id');
        $filhos = auth()->user()->alunosResponsavel;

        $temFiltroData = $request->filled('inicio') || $request->filled('fim');

        $frequencias = Frequencia::with(['aluno', 'turma'])
            ->whereIn('aluno_id', $alunoIds)
            ->when($request->filled('aluno_id'), fn($q) => $q->where('aluno_id', $request->aluno_id))
            ->when($temFiltroData, function ($q) use ($request) {
                $q->when($request->filled('inicio'), fn($q2) => $q2->where('data', '>=', $request->inicio))
                    ->when($request->filled('fim'), fn($q2) => $q2->where('data', '<=', $request->fim));
            }, function ($q) {
                $q->whereDate('data', now()->format('Y-m-d'));
            })
            ->latest('data')
            ->paginate(20);

        return view('frequencia.frequencia-responsavel', compact('frequencias', 'filhos', 'temFiltroData'));
    }

    private function turmasPermitidas($user)
    {
        if ($user->perfil === 'admin') {
            return Turma::orderBy('nome')->get();
        }

        return $user->turmas()->orderBy('nome')->get();
    }
}