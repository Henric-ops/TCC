<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistroDiarioRequest;
use App\Models\Aluno;
use App\Models\Escola;
use App\Models\RegistroDiario;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RegistroDiarioController extends Controller
{
    public function index(Request $request)//método para exibir a lista de registros diários com filtros
    {
        $user = Auth::user();
        $escolaId = $request->input('escola_id');
        $turmaId = $request->input('turma_id');
        $alunoId = $request->input('aluno_id');
        $temFiltroData = $request->filled('inicio') || $request->filled('fim');

        $escolasPermitidas = $this->escolasPermitidas($user);
        $turmasTotais = $this->turmasPermitidas($user)
            ->with('alunos')
            ->orderBy('nome')
            ->get();
        $turmasPermitidas = $turmasTotais
            ->when($escolaId, fn($query) => $query->where('escola_id', $escolaId))
            ->values();

        if ($turmaId && !$turmasPermitidas->contains('id', $turmaId)) {
            $turmaId = null;
        }

        $alunosPermitidos = Aluno::whereIn('id', function ($q) use ($turmasTotais) {
            $q->select('aluno_id')->from('turma_aluno')->whereIn('turma_id', $turmasTotais->pluck('id'));
        })->orderBy('nome')->get();

        $turmasPorEscola = $turmasTotais
            ->groupBy('escola_id')
            ->map(fn($turmas) => $turmas->map(fn($turma) => [
                'id' => $turma->id,
                'nome' => $turma->nome,
            ])->values()->all())
            ->all();

        $alunosPorTurma = [];
        foreach ($turmasTotais as $turma) {
            $alunosPorTurma[$turma->id] = $turma->alunos()
                ->orderBy('nome')
                ->get()
                ->map(fn($aluno) => [
                    'id' => $aluno->id,
                    'nome' => $aluno->nome,
                ])
                ->values()
                ->all();
        }

        $alunosParaFiltro = $turmaId
            ? Turma::findOrFail($turmaId)->alunos()->orderBy('nome')->get()
            : $alunosPermitidos;

        if ($alunoId && !$alunosParaFiltro->contains('id', $alunoId)) {
            $alunoId = null;
        }

        $query = RegistroDiario::with(['aluno', 'professor']);

        if ($user->perfil === 'professor') {
            $query->whereIn('aluno_id', $alunosPermitidos->pluck('id'));
        }

        if ($turmaId) {
            if (!$turmasPermitidas->contains('id', $turmaId)) {
                abort(403, 'Você não tem permissão para acessar esta turma.');
            }

            $query->whereIn(
                'aluno_id',
                $alunosParaFiltro->pluck('id')
            );

        } elseif ($escolaId) {
            if (!$escolasPermitidas->contains('id', $escolaId)) {
                abort(403, 'Você não tem permissão para acessar esta escola.');
            }

            $query->whereIn(
                'aluno_id',
                Aluno::whereIn('id', function ($q) use ($turmasPermitidas) {
                    $q->select('aluno_id')
                        ->from('turma_aluno')
                        ->whereIn('turma_id', $turmasPermitidas->pluck('id'));
                })->pluck('id')
            );
        }

        $query->when($temFiltroData, function ($q) use ($request) {
            $q->when($request->filled('inicio'), fn($q2) => $q2->where('data', '>=', $request->inicio))
                ->when($request->filled('fim'), fn($q2) => $q2->where('data', '<=', $request->fim));
        }, function ($q) {
            $q->whereDate('data', now()->format('Y-m-d'));
        });

        $registros = $query->latest('data')->paginate(15);

        return view('registros.index', compact(
            'registros',
            'escolasPermitidas',
            'turmasPermitidas',
            'alunosParaFiltro',
            'escolaId',
            'turmaId',
            'alunoId',
            'temFiltroData',
            'turmasPorEscola',
            'alunosPorTurma',
            'turmasTotais'
        ));
    }
    public function selecionarAluno(Request $request)
    {
        $turma = Turma::find($request->query('turma_id'));

        if (!$turma || !$this->turmasPermitidas(Auth::user())->contains('id', $turma->id)) {
            return redirect()->route('registros.selecionar-turma')->with('erro', 'Selecione uma turma válida.');
        }

        $alunos = $turma->alunos()->orderBy('nome')->get();

        return view('registros.selecionar-aluno', compact('turma', 'alunos'));
    }

    public function selecionarTurma(Request $request)
    {
        $user = Auth::user();
        $escolaId = $request->input('escola_id');

        $escolasPermitidas = $this->escolasPermitidas($user);
        $turmas = $this->turmasPermitidas($user)
            ->when($escolaId, fn($query) => $query->where('escola_id', $escolaId))
            ->orderBy('nome')
            ->get();

        return view('registros.selecionar-turma', compact('turmas', 'escolasPermitidas', 'escolaId'));
    }



    private function turmasPermitidas($user)
    {
        if ($user->perfil === 'admin') {
            return Turma::query();
        }

        if ($user->perfil === 'professor') {
            return $user->turmas();
        }

        return Turma::query()->whereRaw('1 = 0');
    }

    private function escolasPermitidas($user)
    {
        if ($user->perfil === 'admin') {
            return Escola::orderBy('nome')->get();
        }

        $escolaIds = $this->turmasPermitidas($user)
            ->pluck('escola_id')
            ->filter()
            ->unique();

        return Escola::whereIn('id', $escolaIds)
            ->orderBy('nome')
            ->get();
    }

    public function create(Request $request)
    {
        $aluno = Aluno::find($request->query('aluno_id'));

        if (!$aluno || !$this->alunosPermitidos(Auth::user())->contains('id', $aluno->id)) {
            return redirect()->route('registros.selecionar-aluno')->with('erro', 'Selecione um aluno válido.');
        }

        return view('registros.create', compact('aluno'));
    }

    public function store(StoreRegistroDiarioRequest $request)
    {
        $registro = RegistroDiario::create([
            'aluno_id' => $request->aluno_id,
            'professor_id' => Auth::id(),
            'data' => now()->format('Y-m-d'),
            'observacao' => null,
        ]);

        $this->salvarFilhos($registro, $request);

        return redirect()->route('registros.index')->with('sucesso', 'Registro salvo com sucesso.');
    }

    public function edit(RegistroDiario $registro)// método para exibir o formulário de edição de um registro específico
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
    public function meusRegistros(Request $request)//método para exibir a lista de registros diários do responsável com filtros
    {
        //pega os ids dos alunos relacionados ao responsável mesmo que estejam deletados
        $alunoIds = auth()->user()->alunosResponsavel()->withTrashed()->pluck('alunos.id');
        $filhos = auth()->user()->alunosResponsavel()->withTrashed()->get();

        $temFiltroData = $request->filled('inicio') || $request->filled('fim');

        $registros = RegistroDiario::with(['aluno', 'professor'])
            ->whereIn('aluno_id', $alunoIds)
            ->when($request->filled('aluno_id'), fn($q) => $q->where('aluno_id', $request->aluno_id))
            ->when($temFiltroData, function ($q) use ($request) {
                $q->when($request->filled('inicio'), fn($q2) => $q2->where('data', '>=', $request->inicio))
                    ->when($request->filled('fim'), fn($q2) => $q2->where('data', '<=', $request->fim));
            }, function ($q) {
                $q->whereDate('data', now()->format('Y-m-d'));
            })
            ->latest('data')
            ->paginate(15);

        return view('registros.ver-registro-responsavel', compact('registros', 'filhos', 'temFiltroData'));
    }

    private function salvarFilhos(RegistroDiario $registro, $request): void//método privado para salvar os registros filhos (alimentações, líquidos, sono e fraldas) de um registro diário
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
        $user = Auth::user();

        if ($user->perfil === 'admin') {
            return;
        }

        if ($user->perfil === 'professor' && $registro->professor_id === $user->id) {
            return;
        }

        abort(403);
    }


    public function meuRegistro(RegistroDiario $registro)//método para exibir detalhes de um registro específico para o responsável
    {
        $alunoIds = Auth::user()->alunosResponsavel->pluck('id');

        abort_unless($alunoIds->contains($registro->aluno_id), 403);

        $registro->load('aluno', 'professor', 'alimentacoes', 'sono', 'fraldas', 'liquidos');

        $alimentacaoMap = $registro->alimentacoes->pluck('resultado', 'refeicao');
        $liquidosMap = $registro->liquidos->pluck('resultado', 'tipo');
        $xixiTotal = $registro->fraldas->where('xixi', true)->count();
        $cocoTotal = $registro->fraldas->where('coco', true)->count();
        $observacoesFralda = optional($registro->fraldas->first(fn($f) => filled($f->observacoes)))->observacoes;

        return view('registros.detalhes-registro-responsavel', compact(
            'registro',
            'alimentacaoMap',
            'liquidosMap',
            'xixiTotal',
            'cocoTotal',
            'observacoesFralda'
        ));
    }
}