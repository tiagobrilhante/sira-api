<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicador extends Model
{

    use SoftDeletes;

    protected $fillable = ['nome', 'categoria_id', 'meta', 'tendencia', 'objetivo', 'green', 'yellow_1', 'yellow_2', 'red', 'observacoes'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function indicadorValor()
    {
        return $this->hasMany(IndicadorValor::class);
    }

    public function indicadorValorMesAno($mes, $ano)
    {
        return $this->hasOne(IndicadorValor::class)
            ->where('mes', $mes)
            ->where('ano', $ano);
    }
}
