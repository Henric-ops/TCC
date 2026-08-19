<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->perfil === 'admin';
    }

    public function rules(): array /**/
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6',
            'perfil' => 'required|in:professor,responsavel',
            'alunos' => 'nullable|array',
            'alunos.*' => 'exists:alunos,id',
            'parentesco' => 'required_if:perfil,responsavel|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Já existe um usuário com esse e-mail.',
        ];
    }
}