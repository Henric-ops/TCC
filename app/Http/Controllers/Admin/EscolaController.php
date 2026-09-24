<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEscolaRequest;
use App\Http\Requests\Admin\UpdateEscolaRequest;
use App\Models\Escola;

class EscolaController extends Controller
{
    public function index()
    {
        $escolas = Escola::orderBy('nome')->paginate(15);

        return view('escolas.index', compact('escolas'));
    }

    public function create()
    {
        return view('escolas.create');
    }

    public function store(StoreEscolaRequest $request)
    {
        Escola::create($request->validated());

        return redirect()->route('admin.escolas.index')->with('sucesso', 'Escola cadastrada com sucesso.');
    }

    public function edit(Escola $escola)
    {
        return view('escolas.edit', compact('escola'));
    }

    public function update(UpdateEscolaRequest $request, Escola $escola)
    {
        $escola->update($request->validated());

        return redirect()->route('admin.escolas.index')->with('sucesso', 'Escola atualizada com sucesso.');
    }

    public function destroy(Escola $escola)
    {
        $temVinculos = $escola->usuarios()->exists() || $escola->alunos()->exists() || $escola->turmas()->exists();

        if ($temVinculos) {
            return redirect()->route('admin.escolas.index')
                ->with('erro', 'Essa escola tem usuários, alunos ou turmas vinculados e não pode ser excluída.');
        }

        $escola->delete();

        return redirect()->route('admin.escolas.index')->with('sucesso', 'Escola removida.');
    }
}