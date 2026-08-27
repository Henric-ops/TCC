<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = 'mensagens';

    protected $fillable = ['remetente_id', 'destinatario_id', 'aluno_id', 'conteudo', 'lida', 'enviado_em'];

    protected $casts = [
        'lida' => 'boolean',
        'enviado_em' => 'datetime',
    ];

    public function remetente()
    {
        return $this->belongsTo(Usuario::class, 'remetente_id');
    }

    public function destinatario()
    {
        return $this->belongsTo(Usuario::class, 'destinatario_id');
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}