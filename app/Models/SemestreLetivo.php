<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SemestreLetivo extends Model
{
    use SoftDeletes;

    protected $fillable =['codigo', 'curso_id'];

    // usuarios podem ter várias unidades
    // vai precisar de um objeto associativo separado
    public function curso()
    {
        return $this->belongsTo( Curso::class);
    }

    public function turnos()
    {
        return $this->hasMany( Turno::class);
    }
}
