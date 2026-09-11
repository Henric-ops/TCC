<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\User;
use App\Models\Frequencia;
use App\Models\RegistroDiario;

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

        $pendentes = User::where('status', 'pendente')->orderBy('created_at')->take(5)->get();
        $totalPendentes = User::where('status', 'pendente')->count();

        $registrosHoje = RegistroDiario::whereDate('data', now())->count();

        $turmasComFrequenciaHoje = Frequencia::whereDate('data', now())->distinct('turma_id')->count('turma_id');

        $turmas = Turma::withCount('alunos')->orderByDesc('alunos_count')->get();

        return view('dashboard.admin', compact(
            'user',
            'totalTurmas',
            'totalAlunos',
            'totalProfessores',
            'totalResponsaveis',
            'pendentes',
            'totalPendentes',
            'registrosHoje',
            'turmasComFrequenciaHoje',
            'turmas'
        ));
    }

    public function professor()
    {
        $user = Auth::user();

        $turmas = $user->turmas()->withCount('alunos')->orderBy('nome')->get();
        $totalAlunos = $turmas->sum('alunos_count');

        $registrosHoje = RegistroDiario::where('professor_id', $user->id)
            ->whereDate('data', now()->format('Y-m-d'))
            ->count();

        $turmaIdsComFrequenciaHoje = Frequencia::whereIn('turma_id', $turmas->pluck('id'))
            ->whereDate('data', now()->format('Y-m-d'))
            ->pluck('turma_id')
            ->unique();

        $turmasSemFrequencia = $turmas->whereNotIn('id', $turmaIdsComFrequenciaHoje)->count();

        return view('dashboard.professor', compact(
            'user',
            'turmas',
            'totalAlunos',
            'registrosHoje',
            'turmasSemFrequencia'
        ));
    }

    public function responsavel()
    {
        $user = Auth::user();

        $filhos = $user->alunosResponsavel()->with('turmas')->get();

        $hoje = now()->format('Y-m-d');

        foreach ($filhos as $filho) {
            $filho->frequenciaHoje = Frequencia::where('aluno_id', $filho->id)->where('data', $hoje)->first();
            $filho->registroHoje = RegistroDiario::where('aluno_id', $filho->id)->where('data', $hoje)->first();
        }

        $filhoIds = $filhos->pluck('id');

        $atividadeRecente = RegistroDiario::with(['aluno', 'professor'])
            ->whereIn('aluno_id', $filhoIds)
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('dashboard.responsavel', compact('user', 'filhos', 'atividadeRecente'));
    }
}