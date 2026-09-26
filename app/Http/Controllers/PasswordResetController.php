<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function solicitar()
    {
        return view('auth.esqueci-senha');
    }

    public function enviarLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('sucesso', 'Enviamos um link de redefinição pro seu e-mail.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function formulario(string $token, Request $request)
    {
        return view('auth.redefinir-senha', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function redefinir(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'senha' => 'required|min:8|confirmed',
        ]);

        // Password::reset() exige a chave 'password' internamente — mapeamos o seu campo 'senha' pra ela aqui
        $status = Password::reset(
            [
                'email' => $request->email,
                'password' => $request->senha,
                'password_confirmation' => $request->senha_confirmation,
                'token' => $request->token,
            ],
            function ($user, $password) {
                $user->forceFill(['senha' => $password])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('sucesso', 'Senha redefinida com sucesso! Faça login.')
            : back()->withErrors(['email' => __($status)]);
    }
}