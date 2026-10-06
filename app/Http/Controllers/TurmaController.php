<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurmaController extends Controller
{
    public function index(Request $request)
    {
        $turmas = Auth::user()->turmas()
            ->with('escola')
            ->withCount('alunos')
            ->orderBy('nome')
            ->get();

        $escolasPermitidas = $turmas
            ->pluck('escola')
            ->unique('id')
            ->sortBy('nome')
            ->values();
        $escolaId = $request->query('escola_id');

        $turmasFiltradas = $turmas->when(
            $escolaId,
            fn($collection) => $collection->where('escola_id', $escolaId)
        );

        $turmasPorEscola = $turmasFiltradas
            ->groupBy('escola_id')
            ->sortBy(fn($turmasDaEscola) => $turmasDaEscola->first()->escola->nome);

        return view('turmas.minhas', compact('turmas', 'turmasPorEscola', 'escolasPermitidas', 'escolaId'));
    }

    public function show(Turma $turma)
    {
        abort_unless(Auth::user()->turmas->contains('id', $turma->id), 403);

        $turma->load('alunos');

        return view('turmas.minha', compact('turma'));
    }
}