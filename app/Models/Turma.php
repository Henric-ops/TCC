<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $fillable = ['escola_id', 'nome', 'ano', 'periodo'];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function professores()//método para obter os professores vinculados à turma
    {
        return $this->belongsToMany(
            User::class,
            'turma_professor',
            'turma_id',
            'usuario_id'
        );
    }

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class, 'turma_aluno');
    }
}
