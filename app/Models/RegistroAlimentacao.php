<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroAlimentacao extends Model
{
    protected $fillable = ['registro_id', 'refeicao', 'resultado'];

    public function registroDiario()
    {
        return $this->belongsTo(RegistroDiario::class, 'registro_id');
    }
}
