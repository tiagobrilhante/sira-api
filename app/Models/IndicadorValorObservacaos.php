<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndicadorValorObservacaos extends Model
{

    use SoftDeletes;

    protected $fillable =['observacao', 'resp', 'user_id', 'indicador_valor_id'];
    public function users()
    {
        return $this->hasMany( User::class);
    }

    public function indicadorValor()
    {
        return $this->belongsTo( IndicadorValor::class);
    }
}
