<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $fillable = ['escola_id', 'nome', 'data_nascimento', 'foto'];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'turma_aluno');
    }

    public function responsaveis()
    {
        return $this->belongsToMany(Usuario::class, 'responsavel_aluno')->withPivot('parentesco');
    }

    public function registrosDiarios()
    {
        return $this->hasMany(RegistroDiario::class);
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }
}
