<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{

    use SoftDeletes;

    protected $fillable = ['nome', 'secao_id', 'natureza', 'periodicidade', 'mapeamento_total_anual', 'mapeamento_total_mensal', 'ativo', 'observacoes'];

    protected $appends = ['numindicador'];
    public function secao()
    {
        return $this->belongsTo(Secao::class);
    }

    public function indicadores()
    {
        return $this->hasMany(Indicador::class);
    }

    public function getNumindicadorAttribute()
    {
        return $this->indicadores()->count();
    }


}
