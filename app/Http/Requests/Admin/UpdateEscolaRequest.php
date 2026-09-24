<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class UpdateEscolaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->perfil === 'admin';
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'cnpj' => ['required', 'string', 'max:20', Rule::unique('escolas', 'cnpj')->ignore($this->escola->id)],
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ];
    }
}