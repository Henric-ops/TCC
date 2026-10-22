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

            'alimentacao.colacao' => 'required|in:tudo,parte,rejeitou',
            'alimentacao.almoco' => 'required|in:tudo,parte,rejeitou',
            'alimentacao.lanche' => 'required|in:tudo,parte,rejeitou',
            'alimentacao.jantar' => 'required|in:tudo,parte,rejeitou',

            'liquidos.leite' => 'required|in:tudo,parte,rejeitou',
            'liquidos.suco' => 'required|in:tudo,parte,rejeitou',
            'liquidos.agua' => 'required|in:tudo,parte,rejeitou',

            'dormiu' => 'required|in:sim,nao',
            'sono_inicio_1' => 'required|date_format:H:i',
            'sono_fim_1' => 'required|date_format:H:i',
            'sono_inicio_2' => 'required|date_format:H:i',
            'sono_fim_2' => 'required|date_format:H:i',

            'xixi' => 'required|integer|min:0',
            'coco' => 'required|integer|min:0',
            'observacoes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'alimentacao.colacao.required' => 'Informe a alimentação da colação.',
            'alimentacao.almoco.required' => 'Informe a alimentação do almoço.',
            'alimentacao.lanche.required' => 'Informe a alimentação do lanche.',
            'alimentacao.jantar.required' => 'Informe a alimentação do jantar.',
            'alimentacao.*.in' => 'Selecione uma opção válida para a alimentação.',
            'liquidos.leite.required' => 'Informe o consumo de leite.',
            'liquidos.suco.required' => 'Informe o consumo de suco.',
            'liquidos.agua.required' => 'Informe o consumo de água.',
            'liquidos.*.in' => 'Selecione uma opção válida para os líquidos.',
            'dormiu.required' => 'Informe se o aluno dormiu.',
            'dormiu.in' => 'Selecione uma opção válida para o sono.',
            'sono_inicio_1.required' => 'Informe o início do primeiro período de sono.',
            'sono_inicio_1.date_format' => 'Informe um horário válido para o início do primeiro período.',
            'sono_fim_1.required' => 'Informe o fim do primeiro período de sono.',
            'sono_fim_1.date_format' => 'Informe um horário válido para o fim do primeiro período.',
            'sono_inicio_2.required' => 'Informe o início do segundo período de sono.',
            'sono_inicio_2.date_format' => 'Informe um horário válido para o início do segundo período.',
            'sono_fim_2.required' => 'Informe o fim do segundo período de sono.',
            'sono_fim_2.date_format' => 'Informe um horário válido para o fim do segundo período.',
            'xixi.required' => 'Informe a quantidade de trocas por xixi.',
            'xixi.integer' => 'A quantidade de trocas por xixi deve ser um número inteiro.',
            'xixi.min' => 'A quantidade de trocas por xixi não pode ser negativa.',
            'coco.required' => 'Informe a quantidade de trocas por cocô.',
            'coco.integer' => 'A quantidade de trocas por cocô deve ser um número inteiro.',
            'coco.min' => 'A quantidade de trocas por cocô não pode ser negativa.',
            'observacoes.max' => 'A observação não pode ter mais de 500 caracteres.',
        ];
    }
}