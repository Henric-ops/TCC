<?php

namespace App\Http\Controllers;

use App\Models\Escola;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
	public function showRegister(): View
	{
		$escolas = Escola::orderBy('nome')->get();

		return view('auth.register', compact('escolas'));
	}

	public function register(Request $request): RedirectResponse
	{
		$dados = $request->validate([
			'nome' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
			'senha' => ['required', 'string', 'min:8', 'confirmed'],
			'perfil' => ['required', 'in:professor,responsavel'],
			'escola_id' => ['required', 'exists:escolas,id'],
		]);

		User::create($dados);

		return redirect()->route('login')->with(
			'sucesso',
			'Cadastro realizado com sucesso. Aguarde a aprovação do administrador.'
		);
	}
}
