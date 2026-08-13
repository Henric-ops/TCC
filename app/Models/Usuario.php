<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = ['escola_id', 'nome', 'email', 'senha', 'perfil'];

    protected $hidden = ['senha'];

    protected $casts = [
        'senha' => 'hashed', // hash automático ao salvar
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'turma_professor');
    }

    public function alunosResponsavel()
    {
        return $this->belongsToMany(Aluno::class, 'responsavel_aluno')->withPivot('parentesco');
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
