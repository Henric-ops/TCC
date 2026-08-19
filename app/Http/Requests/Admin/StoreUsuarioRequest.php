<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->perfil === 'admin';
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required|string|min:6',
            'perfil' => 'required|in:professor,responsavel',
            'escola_id' => 'required|exists:escolas,id',
            'alunos' => 'nullable|array',
            'alunos.*' => [
                Rule::exists('alunos', 'id')->where(fn ($query) =>
                    $query->where('escola_id', $this->input('escola_id'))
                ),
            ],
            'parentesco' => 'nullable|string|max:100|required_if:perfil,responsavel',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Já existe um usuário com esse e-mail.',
        ];
    }
}