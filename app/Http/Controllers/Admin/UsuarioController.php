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
    public function index(Request $request)
    {
        $baseQuery = User::where('perfil', '!=', 'admin');

        // Filtro por perfil
        $query = clone $baseQuery;

        if ($request->filled('perfil')) {
            $query->where('perfil', $request->perfil);
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Busca por nome ou e-mail
        if ($request->filled('busca')) {
            $busca = $request->busca;

            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%");
            });
        }

        $usuarios = $query
            ->with(['alunosResponsavel', 'turmas'])
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        // Quantidade de usuários por perfil
        $totalUsuarios = (clone $baseQuery)->count();

        $totalProfessores = (clone $baseQuery)
            ->where('perfil', 'professor')
            ->count();

        $totalResponsaveis = (clone $baseQuery)
            ->where('perfil', 'responsavel')
            ->count();


        $totalEscolas = (clone $baseQuery)
            ->where('perfil', 'escola')
            ->count();

        return view('usuarios.index', compact(
            'usuarios',
            'totalUsuarios',
            'totalProfessores',
            'totalResponsaveis'
        ));
    }

    public function create()
    {
        $escolas = Escola::orderBy('nome')->get();
        $alunos = Aluno::orderBy('nome')->get();

        return view('usuarios.create', compact('escolas', 'alunos'));
    }

    public function store(StoreUsuarioRequest $request)// cria e vincula usuário a alunos ou escolas
    {
        $usuario = User::create([
            'escola_id' => $request->perfil === 'professor' ? null : $request->escola_id,
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => $request->senha,
            'perfil' => $request->perfil,
            'status' => 'aprovado',
        ]);

        if ($request->perfil === 'professor') {
            $usuario->escolas()->attach($request->input('escolas', []));
        }

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
        $escolasVinculadas = $usuario->escolas->pluck('id')->toArray();

        return view('usuarios.edit', compact('usuario', 'escolas', 'alunos', 'alunosVinculados', 'escolasVinculadas'));
    }

    public function aprovar(Request $request, User $usuario)//método para aprovar o usuário e vincular aluno ou turma
    {
        if ($usuario->status !== 'pendente') {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('erro', 'Este cadastro já foi processado.');
        }

        if ($usuario->perfil === 'responsavel') {

            $request->validate([
                'alunos' => ['required', 'array', 'min:1'],
                'alunos.*' => ['required', 'integer', 'distinct', 'exists:alunos,id'],
                'parentesco' => ['required', 'string', 'max:100'],
            ]);

            $vinculos = collect($request->alunos)->mapWithKeys(fn($alunoId) => [
                $alunoId => [
                    'parentesco' => $request->parentesco,
                ],
            ]);
            $usuario->alunosResponsavel()->sync($vinculos);

        } elseif ($usuario->perfil === 'professor') {

            $request->validate([
                'turmas' => ['required', 'array', 'min:1'],
                'turmas.*' => ['required', 'integer', 'distinct', 'exists:turmas,id'],
            ]);

            $turmasSelecionadas = \App\Models\Turma::whereIn('id', $request->turmas)->get();

            $usuario->turmas()->sync($turmasSelecionadas->modelKeys());
            $usuario->escolas()->syncWithoutDetaching([
                ...$turmasSelecionadas->pluck('escola_id')->unique()->all(),
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
        $usuario->escola_id = $request->perfil === 'professor' ? null : $request->escola_id;
        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->perfil = $request->perfil;

        if ($request->filled('senha')) {
            $usuario->senha = $request->senha;
        }

        $usuario->save();

        if ($request->perfil === 'professor') {
            $usuario->escolas()->sync($request->input('escolas', []));
            $usuario->alunosResponsavel()->detach();

        } elseif ($request->perfil === 'responsavel') {
            $pivotData = collect($request->alunos ?? [])->mapWithKeys(fn($alunoId) => [
                $alunoId => ['parentesco' => $request->parentesco],
            ]);
            $usuario->alunosResponsavel()->sync($pivotData);
            $usuario->escolas()->detach();
        }

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário atualizado com sucesso.');
    }


    public function verificar(User $usuario)//método para verificar o usuário e exibir alunos e turmas disponíveis
    {
        $alunos = Aluno::orderBy('nome')->get();

        $turmas = \App\Models\Turma::with('escola')
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

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário removido. O histórico dele continua preservado.');
    }
}