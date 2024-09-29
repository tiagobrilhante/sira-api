<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use SoftDeletes;

    protected $fillable =['nome','prefixo'];

    // usuarios podem ter várias unidades
    // vai precisar de um objeto associativo separado
    public function cursos()
    {
        return $this->hasMany( Curso::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($unidade) {
            $unidade->cursos->each(function ($curso) {
                // Para cada curso, acessar os semestres letivos
                $curso->semestresLetivos->each(function ($semestre) {
                    // Para cada semestre letivo, apagar os turnos relacionados
                    $semestre->turnos()->delete(); // Apaga todos os turnos do semestre letivo
                    $semestre->delete(); // Apaga o semestre letivo
                });
                $curso->delete(); // Apaga o curso
            });
        });
    }
}
