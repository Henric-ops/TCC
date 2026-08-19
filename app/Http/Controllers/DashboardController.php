<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

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

    public function admin()
    {
        $user = Auth::user();

        return view('dashboard.admin', compact('user'));
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
