<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    protected $fillable = ['nome', 'cnpj', 'endereco', 'telefone', 'email'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }
}
