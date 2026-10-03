<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = 'mensagens';

    protected $fillable = [
        'remetente_id',
        'destinatario_id',
        'aluno_id',
        'assunto',
        'conteudo',
        'status',
        'enviado_em',
    ];

    protected $casts = [
        'enviado_em' => 'datetime',
    ];


    public function remetente()
    {
        return $this->belongsTo(User::class, 'remetente_id')->withTrashed();
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id')->withTrashed();
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class)->withTrashed();
    }
}