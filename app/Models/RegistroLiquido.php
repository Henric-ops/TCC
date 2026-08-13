<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroLiquido extends Model
{
    protected $fillable = ['registro_id', 'tipo', 'resultado'];

    public function registroDiario()
    {
        return $this->belongsTo(RegistroDiario::class, 'registro_id');
    }
}
