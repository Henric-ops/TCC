<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'senha' => ['required'],
        ]);

        if (
            Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['senha'],
            ])
        ) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'aprovado') {
                $mensagem = $user->status === 'pendente'
                    ? 'Seu cadastro ainda está aguardando aprovação do administrador.'
                    : 'Seu cadastro foi recusado. Entre em contato com a escola.';

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => $mensagem])->onlyInput('email');
            }

            if ($user->perfil === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->perfil === 'professor') {
                return redirect()->route('professor.dashboard');
            }

            if ($user->perfil === 'responsavel') {
                return redirect()->route('responsavel.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'E-mail ou senha inválidos.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}