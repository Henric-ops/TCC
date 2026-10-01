<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Boletim extends Model
{
    protected $table = 'boletins';

    protected $fillable = [
        'aluno_id',
        'usuario_id',
        'periodo',
        'observacao',
        'arquivo_pdf',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}