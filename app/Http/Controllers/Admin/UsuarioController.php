<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsuarioRequest;
use App\Http\Requests\Admin\UpdateUsuarioRequest;
use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Escola;
use App\Models\User;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::where('perfil', '!=', 'admin')
            ->with(['alunosResponsavel', 'turmas'])
            ->orderBy('nome')
            ->paginate(15);

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $escolas = Escola::orderBy('nome')->get();
        $alunos = Aluno::orderBy('nome')->get();

        return view('usuarios.create', compact('alunos', 'escolas'));
    }

    public function store(StoreUsuarioRequest $request)
    {
        $usuario = User::create([
            'escola_id' => $request->escola_id,
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => $request->senha,
            'perfil' => $request->perfil,
            'status' => 'pendente',
        ]);

        if ($request->perfil === 'responsavel' && $request->filled('alunos')) {
            $pivotData = collect($request->alunos)->mapWithKeys(fn($alunoId) => [
                $alunoId => ['parentesco' => $request->parentesco],
            ]);

            $usuario->alunosResponsavel()->attach($pivotData);
        }

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário cadastrado com sucesso.');
    }

    public function edit(User $usuario)
    {
        $escolas = Escola::orderBy('nome')->get();
        $alunos = Aluno::orderBy('nome')->get();
        $alunosVinculados = $usuario->alunosResponsavel->pluck('id')->toArray();

        return view('usuarios.edit', compact('usuario', 'alunos', 'alunosVinculados', 'escolas'));
    }

    public function aprovar(Request $request, User $usuario)//método para aprovar o usuário e vincular ao aluno ou turma
    {
        if ($usuario->status !== 'pendente') {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('erro', 'Este cadastro já foi processado.');
        }

        if ($usuario->perfil === 'responsavel') {

            $request->validate([
                'aluno_id' => ['required', 'exists:alunos,id'],
                'parentesco' => ['required', 'string', 'max:100'],
            ]);

            $aluno = Aluno::findOrFail($request->aluno_id);

            if ($aluno->escola_id !== $usuario->escola_id) {
                return back()
                    ->withErrors([
                        'aluno_id' => 'O aluno selecionado pertence a outra escola.'
                    ])
                    ->withInput();
            }

            $usuario->alunosResponsavel()->sync([
                $aluno->id => [
                    'parentesco' => $request->parentesco,
                ],
            ]);

        } elseif ($usuario->perfil === 'professor') {

            $request->validate([
                'turma_id' => ['required', 'exists:turmas,id'],
            ]);

            $turma = \App\Models\Turma::findOrFail($request->turma_id);

            if ($turma->escola_id !== $usuario->escola_id) {
                return back()
                    ->withErrors([
                        'turma_id' => 'A turma selecionada pertence a outra escola.'
                    ])
                    ->withInput();
            }

            $usuario->turmas()->sync([
                $turma->id,
            ]);

        } else {

            return back()->withErrors([
                'perfil' => 'Perfil inválido para aprovação.'
            ]);
        }

        $usuario->status = 'aprovado';
        $usuario->save();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('sucesso', 'Cadastro aprovado e vínculo realizado com sucesso.');
    }

    public function recusar(User $usuario)//método para recusar o usuário
    {
        if ($usuario->status !== 'pendente') {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('erro', 'Este cadastro já foi processado.');
        }

        $usuario->status = 'recusado';
        $usuario->save();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('sucesso', 'Cadastro recusado.');
    }

    public function update(UpdateUsuarioRequest $request, User $usuario)
    {
        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->perfil = $request->perfil;
        $usuario->escola_id = $request->escola_id;

        if ($request->filled('senha')) {
            $usuario->senha = $request->senha;
        }

        $usuario->save();

        if ($request->perfil === 'responsavel') {
            $pivotData = collect($request->alunos ?? [])->mapWithKeys(fn($alunoId) => [
                $alunoId => ['parentesco' => $request->parentesco],
            ]);

            $usuario->alunosResponsavel()->sync($pivotData);
        } else {
            $usuario->alunosResponsavel()->detach();
        }

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário atualizado com sucesso.');
    }

    public function verificar(User $usuario)//método para verificar o usuário e exibir os alunos e turmas da escola
    {
        $alunos = Aluno::where('escola_id', $usuario->escola_id)
            ->orderBy('nome')
            ->get();

        $turmas = \App\Models\Turma::where('escola_id', $usuario->escola_id)
            ->orderBy('nome')
            ->get();

        return view('usuarios.verificar', compact(
            'usuario',
            'alunos',
            'turmas'
        ));
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário removido.');
    }
}