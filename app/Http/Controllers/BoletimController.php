<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Boletim;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoletimController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->perfil, ['admin', 'professor']),
            403
        );

        $alunosPermitidos = $this->alunosPermitidos($user);

        $boletins = Boletim::with(['aluno', 'usuario'])
            ->whereIn('aluno_id', $alunosPermitidos->pluck('id'))
            ->latest()
            ->paginate(15);

        return view('boletins.index', compact('boletins'));
    }


    public function create()
    {
        $user = Auth::user();

        // Somente admin e professor podem criar boletins.
        abort_unless(
            in_array($user->perfil, ['admin', 'professor']),
            403
        );

        $alunos = $this->alunosPermitidos($user);

        return view('boletins.create', compact('alunos'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Somente admin e professor podem cadastrar boletins.
        abort_unless(
            in_array($user->perfil, ['admin', 'professor']),
            403
        );

        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'periodo' => 'required|string|max:100',
            'observacao' => 'nullable|string',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'aluno_id.required' => 'Selecione um aluno.',
            'aluno_id.exists' => 'O aluno selecionado não existe.',
            'periodo.required' => 'Informe o período.',
            'arquivo_pdf.mimes' => 'O arquivo deve ser um PDF.',
            'arquivo_pdf.max' => 'O PDF deve ter no máximo 10 MB.',
        ]);

        $alunosPermitidos = $this->alunosPermitidos($user);

        abort_unless(
            $alunosPermitidos->contains('id', $request->aluno_id),
            403
        );

        if (
            !$request->filled('observacao') &&
            !$request->hasFile('arquivo_pdf')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'observacao' => 'Informe uma avaliação ou envie um arquivo PDF.',
                ]);
        }

        $caminhoPdf = null;

        if ($request->hasFile('arquivo_pdf')) {
            $caminhoPdf = $request
                ->file('arquivo_pdf')
                ->store('boletins', 'public');
        }

        Boletim::create([
            'aluno_id' => $request->aluno_id,
            'usuario_id' => $user->id,
            'periodo' => $request->periodo,
            'observacao' => $request->observacao,
            'arquivo_pdf' => $caminhoPdf,
        ]);

        return redirect()
            ->route('boletins.index')
            ->with('sucesso', 'Boletim cadastrado com sucesso.');
    }

    /**
     * Exibe os boletins dos filhos do responsável.
     */
    public function meusBoletins()
    {
        $user = Auth::user();

        // Somente responsáveis podem acessar esta área.
        abort_unless($user->perfil === 'responsavel', 403);

        $alunosIds = $user->alunosResponsavel->pluck('id');

        $boletins = Boletim::with(['aluno', 'usuario'])
            ->whereIn('aluno_id', $alunosIds)
            ->latest()
            ->paginate(15);

        return view('boletins.responsavel-boletim', compact('boletins'));
    }


    public function show(Boletim $boletim)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->perfil, ['admin', 'professor']),
            403
        );

        $alunosPermitidos = $this->alunosPermitidos($user);

        abort_unless(
            $alunosPermitidos->contains('id', $boletim->aluno_id),
            403
        );

        $boletim->load(['aluno', 'usuario']);

        return view('boletins.show', compact('boletim'));
    }


    public function meuBoletim(Boletim $boletim)//exibe um boletim específico para o responsável
    {
        $user = Auth::user();

        abort_unless($user->perfil === 'responsavel', 403);

        $alunosIds = $user->alunosResponsavel->pluck('id');

        abort_unless(
            $alunosIds->contains($boletim->aluno_id),
            403
        );

        $boletim->load(['aluno', 'usuario']);

        return view('boletins.responsavel-ver-boletim', compact('boletim'));
    }


    private function alunosPermitidos($user)
    {
        // Administrador pode acessar todos os alunos.
        if ($user->perfil === 'admin') {
            return Aluno::orderBy('nome')->get();
        }

        // Professor pode acessar somente alunos das suas turmas.
        return Turma::whereHas(
            'professores',
            fn($q) => $q->where('usuario_id', $user->id)
        )
            ->with('alunos')
            ->get()
            ->pluck('alunos')
            ->flatten()
            ->unique('id')
            ->sortBy('nome')
            ->values();
    }
}