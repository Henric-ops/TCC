<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()?->perfil === 'admin';
    }

    public function rules(): array // metodo que define as regras de validação para o request
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('usuarios', 'email')->ignore($this->usuario->id)],
            'senha' => 'nullable|string|min:6',
            'perfil' => 'required|in:professor,responsavel',
            'escola_id' => 'required_unless:perfil,professor|nullable|exists:escolas,id',
            'escolas' => 'required_if:perfil,professor|nullable|array',
            'escolas.*' => 'exists:escolas,id',
            'alunos' => 'nullable|array',
            'alunos.*' => 'exists:alunos,id',
            'parentesco' => 'nullable|string|max:100|required_if:perfil,responsavel',
        ];

    }
}