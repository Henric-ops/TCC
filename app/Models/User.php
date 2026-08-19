<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $fillable = ['escola_id', 'nome', 'email', 'senha', 'perfil', 'status'];
    protected $hidden = ['senha'];
    protected $casts = [
        'senha' => 'hashed',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }
    public function turmas()//método para obter as turmas vinculadas ao usuário
    {
        return $this->belongsToMany(
            Turma::class,
            'turma_professor',
            'usuario_id',
            'turma_id'
        );
    }

    public function alunosResponsavel()//método para obter os alunos vinculados ao responsável
    {
        return $this->belongsToMany(
            Aluno::class,
            'responsavel_aluno',
            'usuario_id',
            'aluno_id'
        )->withPivot('parentesco');
    }

    public function mensagensEnviadas()
    {
        return $this->hasMany(Mensagem::class, 'remetente_id');
    }

    public function mensagensRecebidas()
    {
        return $this->hasMany(Mensagem::class, 'destinatario_id');
    }
}