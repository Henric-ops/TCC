<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRegistroDiarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(Auth::user()?->perfil, ['admin', 'professor']);
    }


    public function rules(): array
    {
        return [
            'aluno_id' => 'required|exists:alunos,id',

            'alimentacao.colacao' => 'nullable|in:tudo,parte,rejeitou',
            'alimentacao.almoco' => 'nullable|in:tudo,parte,rejeitou',
            'alimentacao.lanche' => 'nullable|in:tudo,parte,rejeitou',
            'alimentacao.jantar' => 'nullable|in:tudo,parte,rejeitou',

            'liquidos.leite' => 'nullable|in:tudo,parte,rejeitou',
            'liquidos.suco' => 'nullable|in:tudo,parte,rejeitou',
            'liquidos.agua' => 'nullable|in:tudo,parte,rejeitou',

            'dormiu' => 'nullable|in:sim,nao',
            'sono_inicio_1' => 'nullable|date_format:H:i',
            'sono_fim_1' => 'nullable|date_format:H:i',
            'sono_inicio_2' => 'nullable|date_format:H:i',
            'sono_fim_2' => 'nullable|date_format:H:i',

            'xixi' => 'nullable|integer|min:0',
            'coco' => 'nullable|integer|min:0',
            'observacoes' => 'nullable|string|max:500',
        ];
    }
}