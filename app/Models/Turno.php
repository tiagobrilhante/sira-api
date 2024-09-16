<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turno extends Model
{
    use SoftDeletes;

    protected $fillable =['nome', 'codigo_identificador', 'turma_id'];

    // usuarios podem ter várias unidades
    // vai precisar de um objeto associativo separado
    public function turma()
    {
        return $this->belongsTo( Curso::class);
    }
}
