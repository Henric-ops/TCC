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
}