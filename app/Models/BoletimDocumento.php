<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoletimDocumento extends Model
{
    protected $table = 'boletim_documentos';

    protected $fillable = [
        'boletim_id',
        'nome_original',
        'mime',
        'tamanho',
        'conteudo',
    ];

    protected $hidden = ['conteudo'];

    public function boletim(): BelongsTo
    {
        return $this->belongsTo(Boletim::class);
    }
}