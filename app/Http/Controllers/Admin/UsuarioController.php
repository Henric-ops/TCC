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

    public function store(StoreUsuarioRequest $request)
    {
        if ($request->perfil === 'responsavel') {
            $this->validarAlunosDaEscola($request->escola_id, $request->input('alunos', []));
        }

        $usuario = User::create([
            'escola_id' => $request->escola_id,
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => $request->senha,
            'perfil' => $request->perfil,
            'status' => 'aprovado',
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

        return view('usuarios.edit', compact('usuario', 'escolas', 'alunos', 'alunosVinculados'));
    }

    public function aprovar(Request $request, User $usuario)//método para aprovar o usuário e vincular ao aluno ou turma
    {

        $request->validate([
            'alunos' => $usuario->perfil === 'responsavel' ? 'required|array|min:1' : 'nullable|array',
            'alunos.*' => 'exists:alunos,id',
            'parentesco' => $usuario->perfil === 'responsavel' ? 'required|string|max:100' : 'nullable',
        ]);

        if ($usuario->perfil === 'responsavel') {
            $this->validarAlunosDaEscola($usuario->escola_id, $request->input('alunos', []));
        }
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

            if ((int) $aluno->escola_id !== (int) $usuario->escola_id) {
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

            if ((int) $turma->escola_id !== (int) $usuario->escola_id) {
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
        $escolaMudou = (int) $usuario->escola_id !== (int) $request->escola_id;

        if ($request->perfil === 'responsavel') {
            $this->validarAlunosDaEscola($request->escola_id, $request->input('alunos', []));
        }

        $usuario->escola_id = $request->escola_id;
        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->perfil = $request->perfil;

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

        if ($request->perfil !== 'professor' || $escolaMudou) {
            $usuario->turmas()->detach();
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

    public function destroy(User $usuario)//método para excluir o usuário, mas apenas se ele não tiver histórico de mensagens ou registros
    {
        $temHistorico = $usuario->mensagensEnviadas()->exists()
            || $usuario->mensagensRecebidas()->exists()
            || \App\Models\RegistroDiario::where('professor_id', $usuario->id)->exists();

        if ($temHistorico) {
            return redirect()->route('admin.usuarios.index')
                ->with('erro', 'Esse usuário já tem registros ou mensagens no sistema e não pode ser excluído. Recuse o acesso dele em vez de apagar.');
        }

        $usuario->turmas()->detach();
        $usuario->alunosResponsavel()->detach();
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário removido.');
    }


    private function validarAlunosDaEscola($escolaId, array $alunoIds): void
    {
        if (empty($alunoIds)) {
            return;
        }

        $foraDaEscola = Aluno::whereIn('id', $alunoIds)->where('escola_id', '!=', $escolaId)->exists();

        abort_if($foraDaEscola, 422, 'Um ou mais alunos selecionados não pertencem à escola escolhida.');
    }
}