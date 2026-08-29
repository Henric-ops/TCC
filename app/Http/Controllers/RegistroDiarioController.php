<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistroDiarioRequest;
use App\Models\Aluno;
use App\Models\RegistroDiario;
use App\Models\Turma;
use Illuminate\Http\Request;

class RegistroDiarioController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = RegistroDiario::with(['aluno', 'professor'])->latest('data');

        if ($user->perfil === 'professor') {
            $query->whereIn('aluno_id', $this->alunosPermitidos($user)->pluck('id'));
        }

        $registros = $query->paginate(15);

        return view('registros.index', compact('registros'));
    }

    public function selecionarAluno()
    {
        $alunos = $this->alunosPermitidos(auth()->user());

        return view('registros.selecionar-aluno', compact('alunos'));
    }

    public function create(Request $request)
    {
        $aluno = Aluno::find($request->query('aluno_id'));

        if (!$aluno || !$this->alunosPermitidos(auth()->user())->contains('id', $aluno->id)) {
            return redirect()->route('registros.selecionar-aluno')->with('erro', 'Selecione um aluno válido.');
        }

        return view('registros.create', compact('aluno'));
    }

    public function store(StoreRegistroDiarioRequest $request)
    {
        $registro = RegistroDiario::create([
            'aluno_id' => $request->aluno_id,
            'professor_id' => auth()->id(),
            'data' => now()->format('Y-m-d'),
            'observacao' => null,
        ]);

        $this->salvarFilhos($registro, $request);

        return redirect()->route('registros.index')->with('sucesso', 'Registro salvo com sucesso.');
    }

    public function edit(RegistroDiario $registro)
    {
        $this->autorizarAcesso($registro);

        $registro->load('alimentacoes', 'sono', 'fraldas', 'liquidos', 'aluno');

        $alimentacaoAtual = $registro->alimentacoes->pluck('resultado', 'refeicao');
        $liquidosAtual = $registro->liquidos->pluck('resultado', 'tipo');
        $xixiAtual = $registro->fraldas->where('xixi', true)->count();
        $cocoAtual = $registro->fraldas->where('coco', true)->count();
        $observacoesAtual = optional($registro->fraldas->first(fn($f) => filled($f->observacoes)))->observacoes;

        return view('registros.edit', compact(
            'registro',
            'alimentacaoAtual',
            'liquidosAtual',
            'xixiAtual',
            'cocoAtual',
            'observacoesAtual'
        ));
    }

    public function update(StoreRegistroDiarioRequest $request, RegistroDiario $registro)
    {
        $this->autorizarAcesso($registro);

        $registro->alimentacoes()->delete();
        $registro->sono()->delete();
        $registro->fraldas()->delete();
        $registro->liquidos()->delete();

        $this->salvarFilhos($registro, $request);

        return redirect()->route('registros.index')->with('sucesso', 'Registro atualizado com sucesso.');
    }

    public function destroy(RegistroDiario $registro)
    {
        $this->autorizarAcesso($registro);

        $registro->alimentacoes()->delete();
        $registro->sono()->delete();
        $registro->fraldas()->delete();
        $registro->liquidos()->delete();
        $registro->delete();

        return redirect()->route('registros.index')->with('sucesso', 'Registro removido.');
    }

    public function meusRegistros()
    {
        $alunoIds = auth()->user()->alunosResponsavel->pluck('id');

        $registros = RegistroDiario::with(['aluno', 'professor'])
            ->whereIn('aluno_id', $alunoIds)
            ->latest('data')
            ->paginate(15);

        return view('registros.meus', compact('registros'));
    }

    private function salvarFilhos(RegistroDiario $registro, $request): void
    {
        foreach (['colacao', 'almoco', 'lanche', 'jantar'] as $chave) {
            $status = $request->input("alimentacao.$chave");
            if ($status) {
                $registro->alimentacoes()->create(['refeicao' => $chave, 'resultado' => $status]);
            }
        }

        foreach (['leite', 'suco', 'agua'] as $chave) {
            $status = $request->input("liquidos.$chave");
            if ($status) {
                $registro->liquidos()->create(['tipo' => $chave, 'resultado' => $status]);
            }
        }

        if ($request->filled('dormiu')) {
            $registro->sono()->create([
                'dormiu' => $request->input('dormiu') === 'sim',
                'inicio_1' => $request->input('sono_inicio_1') ?: null,
                'fim_1' => $request->input('sono_fim_1') ?: null,
                'inicio_2' => $request->input('sono_inicio_2') ?: null,
                'fim_2' => $request->input('sono_fim_2') ?: null,
            ]);
        }

        $xixi = max(0, (int) $request->input('xixi', 0));
        $coco = max(0, (int) $request->input('coco', 0));
        $observacoesFralda = $request->input('observacoes');
        $primeiraFralda = true;

        for ($i = 0; $i < $xixi; $i++) {
            $registro->fraldas()->create([
                'horario' => now()->format('H:i:s'),
                'xixi' => true,
                'coco' => false,
                'observacoes' => $primeiraFralda ? $observacoesFralda : null,
            ]);
            $primeiraFralda = false;
        }

        for ($i = 0; $i < $coco; $i++) {
            $registro->fraldas()->create([
                'horario' => now()->format('H:i:s'),
                'xixi' => false,
                'coco' => true,
                'observacoes' => $primeiraFralda ? $observacoesFralda : null,
            ]);
            $primeiraFralda = false;
        }

        if ($primeiraFralda && $observacoesFralda) {
            $registro->fraldas()->create([
                'horario' => now()->format('H:i:s'),
                'xixi' => false,
                'coco' => false,
                'observacoes' => $observacoesFralda,
            ]);
        }
    }

    private function alunosPermitidos($user)
    {
        if ($user->perfil === 'admin') {
            return Aluno::orderBy('nome')->get();
        }

        return Turma::whereHas('professores', fn($q) => $q->where('usuario_id', $user->id))
            ->with('alunos')
            ->get()
            ->pluck('alunos')
            ->flatten()
            ->unique('id')
            ->sortBy('nome')
            ->values();
    }

    private function autorizarAcesso(RegistroDiario $registro): void
    {
        $user = auth()->user();

        if ($user->perfil === 'admin') {
            return;
        }

        if ($user->perfil === 'professor' && $registro->professor_id === $user->id) {
            return;
        }

        abort(403);
    }
}