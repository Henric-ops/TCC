<?php

namespace App\Http\Controllers;

use App\Mail\EmailResponsavel;
use App\Models\Aluno;
use App\Models\Mensagem;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function create()//função para criar o email, onde é carregado os alunos e seus responsáveis
    {
        $alunos = $this->alunosPermitidos(auth()->user());

        $alunosEmail = $alunos->map(function ($aluno) {
            return [
                'id' => $aluno->id,
                'responsaveis' => $aluno->responsaveis->map(function ($responsavel) {
                    return [
                        'id' => $responsavel->id,
                        'nome' => $responsavel->nome,
                        'email' => $responsavel->email,
                    ];
                })->values(),
            ];
        })->values();

        return view('emails.create', compact('alunos', 'alunosEmail'));
    }

    public function enviar(Request $request)
    {
        $dados = $request->validate([
            'aluno_id' => ['required', 'exists:alunos,id'],
            'destinatario_id' => ['required', 'exists:usuarios,id'],
            'assunto' => ['required', 'string', 'max:255'],
            'conteudo' => ['required', 'string', 'max:5000'],
        ]);

        $aluno = Aluno::with(['responsaveis', 'escola'])->findOrFail($dados['aluno_id']);

        // Garante que o aluno selecionado está entre os permitidos pra esse usuário
        if (!$this->alunosPermitidos(Auth::user())->contains('id', $aluno->id)) {
            return back()->withErrors([
                'aluno_id' => 'Você não tem permissão para enviar e-mail sobre esse aluno.',
            ])->withInput();
        }

        $responsavel = $aluno->responsaveis->firstWhere('id', $dados['destinatario_id']);

        if (!$responsavel) {
            return back()
                ->withErrors([
                    'destinatario_id' => 'O responsável selecionado não está vinculado a este aluno.',
                ])
                ->withInput();
        }

        if (empty($responsavel->email)) {
            return back()
                ->withErrors([
                    'destinatario_id' => 'O responsável selecionado não possui um e-mail cadastrado.',
                ])
                ->withInput();
        }

        $remetente = Auth::user();

        Mail::to($responsavel->email)->send(//envia o email para o responsável
            new EmailResponsavel(
                $dados['assunto'],
                $dados['conteudo'],
                $aluno->nome,
                $remetente->nome,
                $aluno->escola->nome
            )
        );

        Mensagem::create([
            'remetente_id' => $remetente->id,
            'destinatario_id' => $responsavel->id,
            'aluno_id' => $aluno->id,
            'assunto' => $dados['assunto'],
            'conteudo' => $dados['conteudo'],
            'status' => 'enviado',
            'enviado_em' => now(),
        ]);

        return redirect()
            ->route('emails.create')
            ->with('success', 'E-mail enviado com sucesso!');
    }

    private function alunosPermitidos($user)//função para verificar se o usuário é admin ou professor, e retorna os alunos permitidos
    {
        if ($user->perfil === 'admin') {
            return Aluno::with('responsaveis')->orderBy('nome')->get();
        }

        return Turma::whereHas('professores', fn($q) => $q->where('usuario_id', $user->id))
            ->with('alunos.responsaveis')
            ->get()
            ->pluck('alunos')
            ->flatten()
            ->unique('id')
            ->sortBy('nome')
            ->values();
    }
}