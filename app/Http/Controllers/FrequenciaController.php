<?php

namespace App\Http\Controllers;

use App\Models\Frequencia;
use App\Models\Turma;
use Illuminate\Http\Request;


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

    public function index()
    {
        $user = auth()->user();
        $query = Frequencia::with(['aluno', 'turma'])->latest('data');

        if ($user->perfil === 'professor') {
            $query->whereIn('turma_id', $this->turmasPermitidas($user)->pluck('id'));
        }

        $frequencias = $query->paginate(20);

        return view('frequencia.index', compact('frequencias'));
    }

    public function meusRegistros()
    {
        $alunoIds = auth()->user()->alunosResponsavel->pluck('id');

        $frequencias = Frequencia::with(['aluno', 'turma'])
            ->whereIn('aluno_id', $alunoIds)
            ->latest('data')
            ->paginate(20);

        return view('frequencia.meus', compact('frequencias'));
    }

    private function turmasPermitidas($user)
    {
        if ($user->perfil === 'admin') {
            return Turma::orderBy('nome')->get();
        }

        return $user->turmas()->orderBy('nome')->get();
    }
}