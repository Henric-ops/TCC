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
        'ano',
        'tipo_periodo',
        'numero_periodo',
        'observacao',
        'arquivo_pdf',
    ];

    protected $casts = [
        'ano' => 'integer',
        'numero_periodo' => 'integer',
    ];

    public function getPeriodoAttribute(): string
    {
        $nomes = [
            'bimestre' => 'Bimestre',
            'trimestre' => 'Trimestre',
            'semestre' => 'Semestre',
        ];

        return $this->numero_periodo . 'º ' . ($nomes[$this->tipo_periodo] ?? 'Período');
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}