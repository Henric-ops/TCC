<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()?->perfil === 'admin';
    }

    public function rules(): array
    {
        return [
            'escola_id' => 'required|exists:escolas,id',
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date|before:today',
            'turmas' => 'required|array|min:1|max:1',
            'turmas.*' => 'required|exists:turmas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'escola_id.required' => 'Selecione a escola do aluno.',
            'escola_id.exists' => 'A escola selecionada não existe.',
            'nome.required' => 'Informe o nome do aluno.',
            'nome.max' => 'O nome do aluno não pode ultrapassar 255 caracteres.',
            'data_nascimento.required' => 'Informe a data de nascimento do aluno.',
            'data_nascimento.date' => 'A data de nascimento deve ser válida.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'turmas.required' => 'Selecione uma turma para o aluno.',
            'turmas.min' => 'Selecione pelo menos uma turma para o aluno.',
            'turmas.max' => 'O aluno só pode estar em uma turma.',
            'turmas.*.required' => 'Selecione uma turma válida para o aluno.',
            'turmas.*.exists' => 'A turma selecionada não existe.',
        ];
    }
}