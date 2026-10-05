<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;

class ComunicadosController extends Controller
{
    public function meuDetalhe(Mensagem $mensagem)// exibir os comunicados enviados por email para o usário e permite a visualização do comunicado
    {
        // abort_unless é uma função própria do Laravel que se a condição não for atendida, interrompe a execução e retorna um erro
//funciona como um if, mas de forma direta, evita a necessidade de escrever várias linhas de código para verificar a condição
        abort_unless($mensagem->destinatario_id === auth()->id(), 403);

        $mensagem->load('remetente', 'aluno');

        return view('comunicados.comunicados-responsavel', compact('mensagem'));
    }
}