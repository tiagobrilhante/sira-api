<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndicadorValor extends Model
{
    use SoftDeletes;

    protected $fillable = ['valor', 'valor_float', 'mes', 'ano', 'indicador_id', 'atualizado'];

    public function indicador()
    {
        return $this->belongsTo(Indicador::class);
    }

    // Acessor para 'valor'
    public function getValorAttribute($value)
    {
        return $value !== null ? $value : $this->attributes['valor_float'];
    }

    public function indicadorValorObservacoes()
    {
        return $this->hasMany(IndicadorValorObservacaos::class);
    }
}
