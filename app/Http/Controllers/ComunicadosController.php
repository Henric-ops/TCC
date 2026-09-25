<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;

class ComunicadosController extends Controller
{
    public function meuDetalhe(Mensagem $mensagem)
    {
        abort_unless($mensagem->destinatario_id === auth()->id(), 403);

        $mensagem->load('remetente', 'aluno');

        return view('comunicados.comunicados-responsavel', compact('mensagem'));
    }
}