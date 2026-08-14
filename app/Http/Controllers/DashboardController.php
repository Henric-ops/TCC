<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
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
