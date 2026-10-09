<?php

namespace App\Http\Controllers;

use App\Models\Frequencia;
use App\Models\RegistroDiario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\Escola;

class RelatorioController extends Controller
{
    public function meuRelatorio(Request $request)
    {
        $filhos = Auth::user()
            ->alunosResponsavel()
            ->withTrashed()
            ->get();

        $alunoId = $request->input('aluno_id', $filhos->first()?->id);

        $aluno = $filhos->firstWhere('id', $alunoId);

        abort_unless($aluno, 404);

        [$inicio, $fim, $dados] = $this->montarDados($aluno, $request);

        return view(
            'relatorios.relatorio-responsavel',
            compact('filhos', 'aluno', 'inicio', 'fim', 'dados')
        );
    }

    public function meuRelatorioPdf(Request $request)
    {
        $filhos = Auth::user()
            ->alunosResponsavel()
            ->withTrashed()
            ->get();

        $alunoId = $request->input('aluno_id', $filhos->first()?->id);

        $aluno = $filhos->firstWhere('id', $alunoId);

        abort_unless($aluno, 404);

        [$inicio, $fim, $dados] = $this->montarDados($aluno, $request);

        $pdf = Pdf::loadView(
            'relatorios.relatorio-resp-pdf',
            compact('aluno', 'inicio', 'fim', 'dados')
        );

        return $pdf->download("relatorio-{$aluno->nome}.pdf");
    }

    private function montarDados($aluno, Request $request)
    {
        $inicio = $request->input(
            'inicio',
            now()->startOfMonth()->format('Y-m-d')
        );

        $fim = $request->input(
            'fim',
            now()->format('Y-m-d')
        );

        // Frequência
        $frequencias = Frequencia::where('aluno_id', $aluno->id)
            ->whereBetween('data', [$inicio, $fim])
            ->get();

        $totalDias = $frequencias->count();

        $presencas = $frequencias
            ->where('presente', true)
            ->count();

        $faltas = $totalDias - $presencas;

        $percentualPresenca = $totalDias > 0
            ? round(($presencas / $totalDias) * 100)
            : 0;

        $diasPeriodo = collect();

        $dataAtual = \Carbon\Carbon::parse($inicio);
        $dataFinal = \Carbon\Carbon::parse($fim);

        while ($dataAtual->lte($dataFinal)) {

            $frequenciaDia = $frequencias->first(function ($frequencia) use ($dataAtual) {
                return \Carbon\Carbon::parse($frequencia->data)->isSameDay($dataAtual);
            });

            if ($frequenciaDia) {
                $status = $frequenciaDia->presente
                    ? 'presente'
                    : 'falta';
            } else {
                $status = 'sem-registro';
            }

            $diasPeriodo->push([
                'data' => $dataAtual->copy(),
                'status' => $status,
            ]);

            $dataAtual->addDay();
        }

        // Registros diários
        $registros = RegistroDiario::with([
            'alimentacoes',
            'sono',
            'fraldas'
        ])
            ->where('aluno_id', $aluno->id)
            ->whereBetween('data', [$inicio, $fim])
            ->get();

        // Alimentação
        $alimentacaoContagem = [
            'tudo' => 0,
            'parte' => 0,
            'rejeitou' => 0,
        ];

        foreach ($registros as $registro) {
            foreach ($registro->alimentacoes as $item) {

                if (isset($alimentacaoContagem[$item->resultado])) {
                    $alimentacaoContagem[$item->resultado]++;
                }
            }
        }

        // Sono
        $diasComSono = $registros
            ->filter(
                fn($registro) =>
                    $registro->sono &&
                    $registro->sono->dormiu
            )
            ->count();

        $diasSemSono = $registros
            ->filter(
                fn($registro) =>
                    $registro->sono &&
                    !$registro->sono->dormiu
            )
            ->count();

        $mediaSonoFormatada = '—';

        // Fraldas
        $totalXixi = $registros->sum(
            fn($registro) =>
                $registro->fraldas
                    ->where('xixi', true)
                    ->count()
        );

        $totalCoco = $registros->sum(
            fn($registro) =>
                $registro->fraldas
                    ->where('coco', true)
                    ->count()
        );

        $dados = [
            'totalDias' => $totalDias,
            'presencas' => $presencas,
            'faltas' => $faltas,
            'percentualPresenca' => $percentualPresenca,

            'diasPeriodo' => $diasPeriodo,

            'totalRegistros' => $registros->count(),

            'alimentacaoContagem' => $alimentacaoContagem,

            'diasComSono' => $diasComSono,
            'diasSemSono' => $diasSemSono,
            'mediaSonoFormatada' => $mediaSonoFormatada,

            'totalXixi' => $totalXixi,
            'totalCoco' => $totalCoco,
        ];

        return [$inicio, $fim, $dados];
    }

    public function index()
    {
        $escolas = Escola::orderBy('nome')->get();
        $turmas = Turma::orderBy('nome')->get();

        $alunos = Aluno::with('turmas')
            ->orderBy('nome')
            ->get();

        return view(
            'admin.relatorios.index',
            compact('escolas', 'turmas', 'alunos')
        );
    }

    public function relatorioTurmaPdf(Request $request)
    {
        [$turma, $inicio, $fim, $linhas, $mediaPresencaTurma] =
            $this->montarDadosTurma($request);

        $pdf = Pdf::loadView(
            'admin.relatorios.turma-pdf',
            compact('turma', 'inicio', 'fim', 'linhas', 'mediaPresencaTurma')
        );

        return $pdf->download("relatorio-turma-{$turma->id}.pdf");
    }

    public function relatorioAlunoPdf(Request $request)
    {
        $aluno = $this->buscarAlunoDoRelatorio($request);

        [$inicio, $fim, $dados] =
            $this->montarDados($aluno, $request);

        $pdf = Pdf::loadView(
            'relatorios.relatorio-resp-pdf',
            compact('aluno', 'inicio', 'fim', 'dados')
        );

        return $pdf->download(
            "relatorio-{$aluno->nome}.pdf"
        );
    }

    private function montarDadosTurma(Request $request): array
    {
        $turmaQuery = Turma::with(['alunos', 'escola']);

        if ($request->filled('escola_id')) {
            $turmaQuery->where('escola_id', $request->input('escola_id'));
        }

        $turma = $turmaQuery->findOrFail($request->input('turma_id'));
        $linhas = collect();

        foreach ($turma->alunos as $aluno) {
            [, , $dadosAluno] = $this->montarDados($aluno, $request);

            $linhas->push([
                'aluno' => $aluno,
                'percentualPresenca' => $dadosAluno['percentualPresenca'],
                'faltas' => $dadosAluno['faltas'],
                'totalRegistros' => $dadosAluno['totalRegistros'],
            ]);
        }

        $inicio = $request->input('inicio', now()->startOfMonth()->format('Y-m-d'));
        $fim = $request->input('fim', now()->format('Y-m-d'));
        $mediaPresencaTurma = $linhas->isNotEmpty()
            ? round($linhas->avg('percentualPresenca'))
            : 0;

        return [$turma, $inicio, $fim, $linhas, $mediaPresencaTurma];
    }

    private function buscarAlunoDoRelatorio(Request $request): Aluno
    {
        $alunoQuery = Aluno::with('escola');

        if ($request->filled('escola_id')) {
            $alunoQuery->where('escola_id', $request->input('escola_id'));
        }

        if ($request->filled('turma_id')) {
            $alunoQuery->whereHas('turmas', function ($query) use ($request) {
                $query->where('turmas.id', $request->input('turma_id'));

                if ($request->filled('escola_id')) {
                    $query->where('turmas.escola_id', $request->input('escola_id'));
                }
            });
        }

        return $alunoQuery->findOrFail($request->input('aluno_id'));
    }
}