<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTurmaRequest;
use App\Http\Requests\Admin\UpdateTurmaRequest;
use App\Models\Escola;
use App\Models\Turma;
use App\Models\User;

class TurmasController extends Controller
{
    public function index()
    {
        $escolaId = request('escola_id');
        $escolasPermitidas = Escola::orderBy('nome')->get();

        $turmas = Turma::withCount('alunos')
            ->when($escolaId, function ($query) use ($escolaId) {
                $query->where('escola_id', $escolaId);
            })
            ->orderBy('ano', 'desc')
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('turmas.index', compact('turmas', 'escolasPermitidas', 'escolaId'));
    }

    public function create()
    {
        $escolas = Escola::orderBy('nome')->get();
        $professores = User::where('perfil', 'professor')->where('status', 'aprovado')->orderBy('nome')->get();

        return view('turmas.create', compact('escolas', 'professores'));
    }

    public function store(StoreTurmaRequest $request)
    {
        $this->validarProfessoresDaEscola($request->escola_id, $request->input('professores', []));

        $turma = Turma::create([
            'escola_id' => $request->escola_id,
            'nome' => $request->nome,
            'ano' => $request->ano,
            'periodo' => $request->periodo,
        ]);

        if ($request->filled('professores')) {
            $turma->professores()->attach($request->professores);
        }

        return redirect()->route('admin.turmas.index')->with('sucesso', 'Turma cadastrada com sucesso.');
    }

    public function edit(Turma $turma)
    {
        $escolas = Escola::orderBy('nome')->get();
        $professores = User::where('perfil', 'professor')->where('status', 'aprovado')->orderBy('nome')->get();
        $professoresVinculados = $turma->professores->pluck('id')->toArray();

        return view('turmas.edit', compact('turma', 'escolas', 'professores', 'professoresVinculados'));
    }

    public function update(UpdateTurmaRequest $request, Turma $turma)
    {
        $this->validarProfessoresDaEscola($request->escola_id, $request->input('professores', []));

        $turma->update($request->only('escola_id', 'nome', 'ano', 'periodo'));
        $turma->professores()->sync($request->professores ?? []);

        return redirect()->route('admin.turmas.index')->with('sucesso', 'Turma atualizada com sucesso.');
    }

    //  garante que nenhum professor selecionado é de outra escola
    private function validarProfessoresDaEscola($escolaId, array $professorIds): void
    {
        if (empty($professorIds)) {
            return;
        }

        $foraDaEscola = User::whereIn('id', $professorIds)->where('escola_id', '!=', $escolaId)->exists();

        abort_if($foraDaEscola, 422, 'Um ou mais professores selecionados não pertencem à escola escolhida.');
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();

        return redirect()->route('admin.turmas.index')->with('sucesso', 'Turma removida.');
    }
}