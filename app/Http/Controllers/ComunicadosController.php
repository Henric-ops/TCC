<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensagem;

class ComunicadosController extends Controller
{
    public function meuDetalhe(Request $request, Mensagem $mensagem)// exibir os comunicados enviados por email para o usário e permite a visualização do comunicado
    {
        // abort_unless é uma função própria do Laravel que se a condição não for atendida, interrompe a execução e retorna um erro
//funciona como um if, mas de forma direta, evita a necessidade de escrever várias linhas de código para verificar a condição
        abort_unless((int) $mensagem->destinatario_id === (int) $request->user()->getAuthIdentifier(), 403);

        $mensagem->load('remetente', 'aluno');

        return view('comunicados.comunicados-responsavel', compact('mensagem'));
    }

    public function excluirMeuComunicado(Request $request, int $mensagem)
    {
        Mensagem::whereKey($mensagem)
            ->where('destinatario_id', $request->user()->getAuthIdentifier())
            ->firstOrFail()
            ->delete();

        return redirect()->route('dashboard')->with('success', 'Comunicado apagado com sucesso.');
    }
}