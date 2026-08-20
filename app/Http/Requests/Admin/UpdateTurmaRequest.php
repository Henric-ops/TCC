<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTurmaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->perfil === 'admin';
    }

    public function rules(): array//metodo para definir as regras de validação para o formulário de atualização de turma
    {
        return [
            'escola_id' => 'required|exists:escolas,id',
            'nome' => 'required|string|max:255',
            'ano' => 'required|integer|min:2000|max:2100',
            'periodo' => 'required|in:Manhã,Tarde,Integral',
            'professores' => 'nullable|array',
            'professores.*' => 'exists:usuarios,id',
        ];
    }
}