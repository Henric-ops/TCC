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
        $turmaId = request('turma_id');

        $turmasPermitidas = Turma::orderBy('nome')->get();

        $alunos = Aluno::with(['turmas'])
            ->when($busca, function ($query) use ($busca) {
                $query->where('nome', 'like', '%' . $busca . '%');
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
            'turmasPermitidas',
            'busca',
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
}