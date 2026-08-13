<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroDiario extends Model
{
    protected $fillable = ['aluno_id', 'professor_id', 'data', 'observacao'];

    protected $casts = ['data' => 'date'];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function professor()
    {
        return $this->belongsTo(Usuario::class, 'professor_id');
    }

    public function alimentacoes()
    {
        return $this->hasMany(RegistroAlimentacao::class, 'registro_id');
    }

    public function sono()
    {
        return $this->hasOne(RegistroSono::class, 'registro_id');
    }

    public function fraldas()
    {
        return $this->hasMany(RegistroFralda::class, 'registro_id');
    }

    public function liquidos()
    {
        return $this->hasMany(RegistroLiquido::class, 'registro_id');
    }
}
