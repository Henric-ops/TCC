<?php

namespace App\Http\Controllers;

use App\Models\Frequencia;
use App\Models\RegistroDiario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Turma;
use App\Models\Aluno;

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
        $turmas = Turma::orderBy('nome')->get();

        $alunos = Aluno::with('turmas')
            ->orderBy('nome')
            ->get();

        return view(
            'admin.relatorios.index',
            compact('turmas', 'alunos')
        );
    }

    public function relatorioTurma(Request $request)
    {
        $turma = Turma::with('alunos')
            ->findOrFail($request->input('turma_id'));

        $linhas = collect();

        foreach ($turma->alunos as $aluno) {

            [$inicio, $fim, $dadosAluno] =
                $this->montarDados($aluno, $request);

            $linhas->push([
                'aluno' => $aluno,

                'percentualPresenca' =>
                    $dadosAluno['percentualPresenca'],

                'faltas' =>
                    $dadosAluno['faltas'],

                'totalRegistros' =>
                    $dadosAluno['totalRegistros'],
            ]);
        }

        $mediaPresencaTurma = $linhas->count() > 0
            ? round($linhas->avg('percentualPresenca'))
            : 0;

        return view(
            'admin.relatorios.turma',
            compact(
                'turma',
                'inicio',
                'fim',
                'linhas',
                'mediaPresencaTurma'
            )
        );
    }

    public function relatorioAluno(Request $request)
    {
        $aluno = Aluno::findOrFail(
            $request->input('aluno_id')
        );

        [$inicio, $fim, $dados] =
            $this->montarDados($aluno, $request);

        return view(
            'admin.relatorios.aluno',
            compact('aluno', 'inicio', 'fim', 'dados')
        );
    }

    public function relatorioAlunoPdf(Request $request)
    {
        $aluno = Aluno::findOrFail(
            $request->input('aluno_id')
        );

        [$inicio, $fim, $dados] =
            $this->montarDados($aluno, $request);

        $pdf = Pdf::loadView(
            'relatorios.meu-relatorio-pdf',
            compact('aluno', 'inicio', 'fim', 'dados')
        );

        return $pdf->download(
            "relatorio-{$aluno->nome}.pdf"
        );
    }
}