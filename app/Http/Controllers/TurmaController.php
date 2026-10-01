<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Illuminate\Support\Facades\Auth;

class TurmaController extends Controller
{
    public function index()
    {
        $turmas = Auth::user()->turmas()
            ->with('escola')
            ->withCount('alunos')
            ->orderBy('nome')
            ->get();

        $turmasPorEscola = $turmas
            ->groupBy('escola_id')
            ->sortBy(fn($turmasDaEscola) => $turmasDaEscola->first()->escola->nome);

        return view('turmas.minhas', compact('turmas', 'turmasPorEscola'));
    }

    public function show(Turma $turma)
    {
        abort_unless(Auth::user()->turmas->contains('id', $turma->id), 403);

        $turma->load('alunos');

        return view('turmas.minha', compact('turma'));
    }
}