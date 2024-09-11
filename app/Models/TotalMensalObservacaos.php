<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TotalMensalObservacaos extends Model
{

    use SoftDeletes;

    protected $fillable =['observacao', 'resp', 'user_id', 'categoria_id', 'mes', 'ano'];
    public function users()
    {
        return $this->hasMany( User::class);
    }

    public function categoria()
    {
        return $this->belongsTo( Categoria::class);
    }
}
