<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroFralda extends Model
{
    protected $fillable = ['registro_id', 'horario', 'xixi', 'coco', 'observacoes'];

    protected $casts = ['xixi' => 'boolean', 'coco' => 'boolean'];

    public function registroDiario()
    {
        return $this->belongsTo(RegistroDiario::class, 'registro_id');
    }
}
