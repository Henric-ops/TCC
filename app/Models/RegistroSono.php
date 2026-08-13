<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroSono extends Model
{
    protected $fillable = ['registro_id', 'dormiu', 'inicio_1', 'fim_1', 'inicio_2', 'fim_2'];

    protected $casts = ['dormiu' => 'boolean'];

    public function registroDiario()
    {
        return $this->belongsTo(RegistroDiario::class, 'registro_id');
    }
}
