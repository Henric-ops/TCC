<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->perfil === 'admin';
    }

    public function rules(): array
    {
        return [
            'escola_id' => 'required|exists:escolas,id',
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date|before:today',
            'foto' => 'nullable|image|max:2048',
            'turmas' => 'nullable|array',
            'turmas.*' => 'exists:turmas,id',
        ];
    }
}