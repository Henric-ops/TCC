<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;


class UpdateTurmaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->perfil === 'admin';
    }

    public function rules(): array
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