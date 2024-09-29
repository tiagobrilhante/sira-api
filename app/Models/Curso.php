<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;

    protected $fillable =['nome','codigo','qtd_periodos_possiveis', 'unidade_id'];

    // usuarios podem ter várias unidades
    // vai precisar de um objeto associativo separado
    public function unidade()
    {
        return $this->belongsTo( Unidade::class);
    }

    public function semestresLetivos()
    {
        return $this->hasMany( SemestreLetivo::class);
    }

    public function userCurso()
    {
        return $this->hasMany( UserCurso::class, 'curso_id', 'id');
    }
}
