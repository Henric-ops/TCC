<?php

namespace App\Http\Controllers;

use App\Models\Frequencia;
use App\Models\RegistroDiario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RelatorioController extends Controller
{
    public function meuRelatorio(Request $request)
    {
        $filhos = Auth::user()->alunosResponsavel;

        $alunoId = $request->input('aluno_id', $filhos->first()?->id);
        $aluno = $filhos->firstWhere('id', $alunoId);

        abort_unless($aluno, 404);

        [$inicio, $fim, $dados] = $this->montarDados($aluno, $request);

        return view('relatorios.relatorio-responsavel', compact('filhos', 'aluno', 'inicio', 'fim', 'dados'));
    }

    public function meuRelatorioPdf(Request $request)
    {
        $filhos = Auth::user()->alunosResponsavel;

        $alunoId = $request->input('aluno_id', $filhos->first()?->id);
        $aluno = $filhos->firstWhere('id', $alunoId);

        abort_unless($aluno, 404);

        [$inicio, $fim, $dados] = $this->montarDados($aluno, $request);

        $pdf = Pdf::loadView('relatorios.relatorio-resp-pdf', compact('aluno', 'inicio', 'fim', 'dados'));
        return $pdf->download("relatorio-{$aluno->nome}.pdf");
    }

    private function montarDados($aluno, Request $request)
    {
        $inicio = $request->input('inicio', now()->startOfMonth()->format('Y-m-d'));
        $fim = $request->input('fim', now()->format('Y-m-d'));

        $frequencias = Frequencia::where('aluno_id', $aluno->id)
            ->whereBetween('data', [$inicio, $fim])
            ->get();

        $totalDias = $frequencias->count();
        $presencas = $frequencias->where('presente', true)->count();
        $faltas = $totalDias - $presencas;
        $percentualPresenca = $totalDias > 0 ? round(($presencas / $totalDias) * 100) : 0;

        $registros = RegistroDiario::with(['alimentacoes', 'sono', 'fraldas'])
            ->where('aluno_id', $aluno->id)
            ->whereBetween('data', [$inicio, $fim])
            ->get();

        $alimentacaoContagem = ['tudo' => 0, 'parte' => 0, 'rejeitou' => 0];
        foreach ($registros as $registro) {
            foreach ($registro->alimentacoes as $item) {
                if (isset($alimentacaoContagem[$item->resultado])) {
                    $alimentacaoContagem[$item->resultado]++;
                }
            }
        }

        $diasComSono = $registros->filter(fn($r) => $r->sono && $r->sono->dormiu)->count();
        $diasSemSono = $registros->filter(fn($r) => $r->sono && !$r->sono->dormiu)->count();

        $totalXixi = $registros->sum(fn($r) => $r->fraldas->where('xixi', true)->count());
        $totalCoco = $registros->sum(fn($r) => $r->fraldas->where('coco', true)->count());

        $dados = [
            'totalDias' => $totalDias,
            'presencas' => $presencas,
            'faltas' => $faltas,
            'percentualPresenca' => $percentualPresenca,
            'totalRegistros' => $registros->count(),
            'alimentacaoContagem' => $alimentacaoContagem,
            'diasComSono' => $diasComSono,
            'diasSemSono' => $diasSemSono,
            'totalXixi' => $totalXixi,
            'totalCoco' => $totalCoco,
        ];

        return [$inicio, $fim, $dados];
    }
}