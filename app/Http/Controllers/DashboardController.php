<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user?->perfil === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user?->perfil === 'professor') {
            return redirect()->route('professor.dashboard');
        }

        if ($user?->perfil === 'responsavel') {
            return redirect()->route('responsavel.dashboard');
        }

        return redirect()->route('login');
    }

    public function admin() //metodo para exibir o dashboard do administrador   
    {
        $user = Auth::user();

        $totalTurmas = Turma::count();
        $totalAlunos = Aluno::count();
        $totalProfessores = User::where('perfil', 'professor')->where('status', 'aprovado')->count();
        $totalResponsaveis = User::where('perfil', 'responsavel')->where('status', 'aprovado')->count();
        $pendentes = User::where('status', 'pendente')->count();

        $turmas = Turma::withCount('alunos')->orderBy('nome')->take(5)->get();

        return view('dashboard.admin', compact(
            'user',
            'totalTurmas',
            'totalAlunos',
            'totalProfessores',
            'totalResponsaveis',
            'pendentes',
            'turmas'
        ));
    }

    public function professor()
    {
        $user = Auth::user();

        return view('dashboard.professor', compact('user'));
    }

    public function responsavel()
    {
        $user = Auth::user();

        return view('dashboard.responsavel', compact('user'));
    }
}
