<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodoTurma extends Model
{
    use SoftDeletes;

    protected $fillable =['periodo','turno_id', 'qtd_turmas_por_periodo'];

    public function turno()
    {
        return $this->belongsTo( Turno::class);
    }

}
