<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Boletim;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PDO;

class BoletimController extends Controller
{
    // abort_unless é uma função própria do Laravel que se a condição não for atendida, interrompe a execução e retorna um erro
//funciona como um if, mas de forma direta, evita a necessidade de escrever várias linhas de código para verificar a condição
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
            'usuario',
            'documento:id,boletim_id,nome_original,tamanho',
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

    public function store(Request $request)
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

        DB::transaction(function () use ($request, $user) {

            $boletim = Boletim::create([
                'aluno_id' => $request->aluno_id,
                'usuario_id' => $user->id,
                'ano' => now()->year,
                'tipo_periodo' => $request->tipo_periodo,
                'numero_periodo' => $request->numero_periodo,
                'observacao' => $request->observacao,
                'arquivo_pdf' => null,
            ]);

            if ($request->hasFile('arquivo_pdf')) {
                $arquivo = $request->file('arquivo_pdf');

                // Lê o PDF temporário sem salvá-lo no diretório
                $conteudo = file_get_contents($arquivo->getRealPath());

                if ($conteudo === false) {
                    throw new \RuntimeException('Não foi possível ler o PDF.');
                }

                // Usa parâmetros binários para inserir o LONGBLOB
                $pdo = DB::connection()->getPdo();

                $sql = 'INSERT INTO boletim_documentos
                    (boletim_id, nome_original, mime, tamanho,
                     conteudo, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?)';

                $stmt = $pdo->prepare($sql);

                $data = now()->toDateTimeString();

                $stmt->bindValue(1, $boletim->id, PDO::PARAM_INT);
                $stmt->bindValue(2, $arquivo->getClientOriginalName());
                $stmt->bindValue(3, 'application/pdf');
                $stmt->bindValue(4, $arquivo->getSize(), PDO::PARAM_INT);
                $stmt->bindValue(5, $conteudo, PDO::PARAM_LOB);
                $stmt->bindValue(6, $data);
                $stmt->bindValue(7, $data);

                $stmt->execute();
            }
        });

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
            'usuario',
            'documento:id,boletim_id,nome_original,tamanho',
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
            'usuario',
            'documento:id,boletim_id,nome_original,tamanho',
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
            'usuario',
            'documento:id,boletim_id,nome_original,tamanho',
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
            'boletim-' . Str::slug($boletim->aluno->nome) . '.pdf'
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
            'boletim-' . Str::slug($boletim->aluno->nome) . '.pdf'
        );
    }

    public function documento(Request $request, Boletim $boletim)
    {
        $user = Auth::user();

        if (in_array($user->perfil, ['admin', 'professor'])) {

            $permitido = $this->alunosPermitidos($user)
                ->contains('id', $boletim->aluno_id);

        } elseif ($user->perfil === 'responsavel') {

            $permitido = $user->alunosResponsavel()
                ->where('alunos.id', $boletim->aluno_id)
                ->exists();

        } else {
            $permitido = false;
        }

        abort_unless($permitido, 403);

        $documento = $boletim->documento;

        abort_unless($documento, 404);

        return response()->stream(
            function () use ($documento) {
                echo $documento->conteudo;
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                    'Content-Disposition' =>
                    ($request->boolean('download') ? 'attachment' : 'inline') .
                    '; filename="boletim-' . $boletim->id . '.pdf"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]
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