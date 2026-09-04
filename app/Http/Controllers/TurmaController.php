<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Illuminate\Support\Facades\Auth;

class TurmaController extends Controller
{
    public function index()
    {
        $turmas = Auth::user()->turmas()->withCount('alunos')->orderBy('nome')->get();

        return view('turmas.minhas', compact('turmas'));
    }

    public function show(Turma $turma)
    {
        abort_unless(Auth::user()->turmas->contains('id', $turma->id), 403);

        $turma->load('alunos');

        return view('turmas.minha', compact('turma'));
    }
}