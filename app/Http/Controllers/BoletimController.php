<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Boletim;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoletimController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->perfil, ['admin', 'professor']),
            403
        );

        $alunosPermitidos = $this->alunosPermitidos($user);
        $busca = $request->input('busca');
        $turmaId = $request->input('turma_id');

        $turmasPermitidas = Turma::query()
            ->when(
                $user->perfil === 'professor',
                fn($query) => $query->whereHas(
                    'professores',
                    fn($professores) => $professores->where('usuario_id', $user->id)
                )
            )
            ->orderBy('nome')
            ->get();

        $boletins = Boletim::with([
            'aluno.escola',
            'aluno.turmas',
            'usuario'
        ])
            ->whereIn('aluno_id', $alunosPermitidos->pluck('id'))
            ->when($busca, function ($query) use ($busca) {
                $query->whereHas('aluno', function ($alunoQuery) use ($busca) {
                    $alunoQuery->where('nome', 'like', '%' . $busca . '%');
                });
            })
            ->when($turmaId, function ($query) use ($turmaId) {
                $query->whereHas('aluno.turmas', function ($turmaQuery) use ($turmaId) {
                    $turmaQuery->where('turmas.id', $turmaId);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('boletins.index', compact(
            'boletins',
            'busca',
            'turmaId',
            'turmasPermitidas'
        ));
    }

    public function create()
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->perfil, ['admin', 'professor']),
            403
        );

        if ($user->perfil === 'admin') {
            $escolas = \App\Models\Escola::orderBy('nome')->get();

            $turmas = Turma::with('escola')
                ->orderBy('nome')
                ->get();
        } else {
            $turmas = Turma::with('escola')
                ->whereHas('professores', function ($query) use ($user) {
                    $query->where('usuario_id', $user->id);
                })
                ->orderBy('nome')
                ->get();

            $escolas = $turmas
                ->pluck('escola')
                ->unique('id')
                ->sortBy('nome')
                ->values();
        }

        $alunos = $this->alunosPermitidos($user);

        $anoAtual = now()->year;

        return view('boletins.create', compact(
            'escolas',
            'turmas',
            'alunos',
            'anoAtual'
        ));
    }

    public function store(Request $request)// m
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->perfil, ['admin', 'professor']),
            403
        );

        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'tipo_periodo' => 'required|in:bimestre,trimestre,semestre',
            'numero_periodo' => 'required|integer|min:1|max:4',
            'observacao' => 'nullable|string',
            'arquivo_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'aluno_id.required' => 'Selecione um aluno.',
            'aluno_id.exists' => 'O aluno selecionado não existe.',
            'tipo_periodo.required' => 'Informe o regime letivo.',
            'tipo_periodo.in' => 'Selecione um regime letivo válido.',
            'numero_periodo.required' => 'Informe o período.',
            'numero_periodo.integer' => 'Selecione um período válido.',
            'numero_periodo.min' => 'Selecione um período válido.',
            'numero_periodo.max' => 'Selecione um período válido.',
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

        $ano = now()->year;

        Boletim::create([
            'aluno_id' => $request->aluno_id,
            'usuario_id' => $user->id,
            'ano' => $ano,
            'tipo_periodo' => $request->tipo_periodo,
            'numero_periodo' => $request->numero_periodo,
            'observacao' => $request->observacao,
            'arquivo_pdf' => $caminhoPdf,
        ]);

        return redirect()
            ->route('boletins.index')
            ->with('sucesso', 'Boletim cadastrado com sucesso.');
    }

    public function meusBoletins()
    {
        $user = Auth::user();

        abort_unless($user->perfil === 'responsavel', 403);

        $alunosIds = $user->alunosResponsavel->pluck('id');

        $boletins = Boletim::with([
            'aluno.escola',
            'aluno.turmas',
            'usuario'
        ])
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

        $boletim->load([
            'aluno.escola',
            'aluno.turmas',
            'usuario'
        ]);

        return view('boletins.show', compact('boletim'));
    }

    public function meuBoletim(Boletim $boletim)
    {
        $user = Auth::user();

        abort_unless($user->perfil === 'responsavel', 403);

        $alunosIds = $user->alunosResponsavel->pluck('id');

        abort_unless(
            $alunosIds->contains($boletim->aluno_id),
            403
        );

        $boletim->load([
            'aluno.escola',
            'aluno.turmas',
            'usuario'
        ]);

        return view('boletins.responsavel-ver-boletim', compact('boletim'));
    }

    public function pdf(Boletim $boletim)
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

        $boletim->load([
            'aluno.escola',
            'aluno.turmas',
            'usuario'
        ]);

        abort_unless(
            !empty($boletim->observacao),
            404
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'boletins.pdf',
            compact('boletim')
        );

        return $pdf->download(
            'boletim-' . \Str::slug($boletim->aluno->nome) . '.pdf'
        );
    }

    public function pdfResponsavel(Boletim $boletim)
    {
        $user = Auth::user();

        abort_unless($user->perfil === 'responsavel', 403);

        $alunosIds = $user->alunosResponsavel->pluck('id');

        abort_unless(
            $alunosIds->contains($boletim->aluno_id),
            403
        );

        $boletim->load([
            'aluno.escola',
            'aluno.turmas',
            'usuario'
        ]);

        abort_unless(
            !empty($boletim->observacao),
            404
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'boletins.pdf',
            compact('boletim')
        );

        return $pdf->download(
            'boletim-' . \Str::slug($boletim->aluno->nome) . '.pdf'
        );
    }

    private function alunosPermitidos($user)
    {
        if ($user->perfil === 'admin') {
            return Aluno::orderBy('nome')->get();
        }

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