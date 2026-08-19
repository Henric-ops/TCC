<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->perfil === 'admin';
    }

    public function rules(): array 
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('usuarios', 'email')->ignore($this->usuario->id)],
            'senha' => 'nullable|string|min:6',
            'perfil' => 'required|in:professor,responsavel',
            'alunos' => 'nullable|array',
            'alunos.*' => 'exists:alunos,id',
            'parentesco' => 'required_if:perfil,responsavel|string|max:100',
        ];
    }
}