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
        $turmas = Turma::withCount('alunos')->orderBy('ano', 'desc')->orderBy('nome')->paginate(15);

        return view('turmas.index', compact('turmas'));
    }

    public function create()
    {
        $escolas = Escola::orderBy('nome')->get();
        $professores = User::where('perfil', 'professor')->where('status', 'aprovado')->orderBy('nome')->get();

        return view('turmas.create', compact('escolas', 'professores'));
    }

    public function store(StoreTurmaRequest $request)//método para armazenar uma nova turma no banco de dados
    {
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
        $turma->update($request->only('escola_id', 'nome', 'ano', 'periodo'));
        $turma->professores()->sync($request->professores ?? []);

        return redirect()->route('admin.turmas.index')->with('sucesso', 'Turma atualizada com sucesso.');
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();

        return redirect()->route('admin.turmas.index')->with('sucesso', 'Turma removida.');
    }
}