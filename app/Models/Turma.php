<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use SoftDeletes;

    protected $fillable =['qtd_turmas', 'curso_id'];

    // usuarios podem ter várias unidades
    // vai precisar de um objeto associativo separado
    public function cursos()
    {
        return $this->belongsTo( Curso::class);
    }
    public function turnos()
    {
        return $this->hasMany( Turno::class);
    }
}
