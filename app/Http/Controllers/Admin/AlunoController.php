<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAlunoRequest;
use App\Http\Requests\Admin\UpdateAlunoRequest;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Escola;

class AlunoController extends Controller
{
    public function index()
    {
        $busca = request('busca');
        $escolaId = request('escola_id');
        $turmaId = request('turma_id');

        $escolasPermitidas = Escola::orderBy('nome')->get();
        $turmasPermitidas = Turma::orderBy('nome')->get();

        $alunos = Aluno::with(['turmas'])
            ->when($busca, function ($query) use ($busca) {
                $query->where('nome', 'like', '%' . $busca . '%');
            })
            ->when($escolaId, function ($query) use ($escolaId) {
                $query->where('escola_id', $escolaId);
            })
            ->when($turmaId, function ($query) use ($turmaId) {
                $query->whereHas('turmas', function ($q) use ($turmaId) {
                    $q->where('turmas.id', $turmaId);
                });
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('alunos.index', compact(
            'alunos',
            'escolasPermitidas',
            'turmasPermitidas',
            'busca',
            'escolaId',
            'turmaId'
        ));
    }

    public function create()
    {
        $escolas = Escola::orderBy('nome')->get();
        $turmas = Turma::orderBy('nome')->get();

        return view('alunos.create', compact('escolas', 'turmas'));
    }

    public function store(StoreAlunoRequest $request)
    {
        $this->validarTurmasDaEscola($request->escola_id, $request->input('turmas', []));

        $aluno = Aluno::create([
            'escola_id' => $request->escola_id,
            'nome' => $request->nome,
            'data_nascimento' => $request->data_nascimento,
        ]);

        if ($request->filled('turmas')) {
            $aluno->turmas()->attach($request->turmas);
        }

        return redirect()->route('admin.alunos.index')->with('sucesso', 'Aluno cadastrado com sucesso.');
    }

    public function edit(Aluno $aluno)
    {
        $escolas = Escola::orderBy('nome')->get();
        $turmas = Turma::orderBy('nome')->get();
        $turmasVinculadas = $aluno->turmas->pluck('id')->toArray();

        return view('alunos.edit', compact('aluno', 'escolas', 'turmas', 'turmasVinculadas'));
    }

    public function update(UpdateAlunoRequest $request, Aluno $aluno)
    {
        $this->validarTurmasDaEscola($request->escola_id, $request->input('turmas', []));

        $dados = $request->only('escola_id', 'nome', 'data_nascimento');

        $aluno->update($dados);
        $aluno->turmas()->sync($request->turmas ?? []);

        return redirect()->route('admin.alunos.index')->with('sucesso', 'Aluno atualizado com sucesso.');
    }

    public function destroy(Aluno $aluno)//método para excluir um aluno e verificar se ele possui registros ou mensagens no sistema antes de permitir a exclusão
    {
        $temHistorico = $aluno->mensagens()->exists()
            || $aluno->registrosDiarios()->exists();

        if ($temHistorico) {
            return redirect()->route('admin.alunos.index')
                ->with('erro', 'Esse aluno já possui registros no sistema e não pode ser excluído.');
        }

        $aluno->turmas()->detach();
        $aluno->responsaveis()->detach();

        $aluno->delete();

        return redirect()->route('admin.alunos.index')->with('sucesso', 'Aluno removido.');
    }

    private function validarTurmasDaEscola($escolaId, array $turmaIds): void
    {
        if (empty($turmaIds)) {
            return;
        }

        $foraDaEscola = Turma::whereIn('id', $turmaIds)->where('escola_id', '!=', $escolaId)->exists();

        abort_if($foraDaEscola, 422, 'Uma ou mais turmas selecionadas não pertencem à escola escolhida.');
    }
}