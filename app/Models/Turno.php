<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turno extends Model
{
    use SoftDeletes;

    protected $fillable =['turno_parametro_id', 'semestre_letivo_id', 'qtd_turmas'];

    // usuarios podem ter várias unidades
    // vai precisar de um objeto associativo separado
    public function semestreLetivo()
    {
        return $this->belongsTo( SemestreLetivo::class);
    }

    public function turnoParametro()
    {
        return $this->belongsTo( TurnoParametro::class);
    }
}
