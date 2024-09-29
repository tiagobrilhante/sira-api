<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TurnoParametro extends Model
{
    use SoftDeletes;
    protected $fillable =['horario', 'identificador_horario'];

    // usuarios podem ter várias unidades
    // vai precisar de um objeto associativo separado
    public function turno()
    {
        return $this->hasMany( Turno::class);
    }
}
