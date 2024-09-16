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
}
