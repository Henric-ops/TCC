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

    /**
     * Usuário que enviou o e-mail.
     */
    public function remetente()
    {
        return $this->belongsTo(User::class, 'remetente_id');
    }

    /**
     * Usuário que receberá o e-mail.
     */
    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id');
    }

    /**
     * Aluno relacionado ao e-mail.
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }
}