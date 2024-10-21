<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAtendimentoResolucao extends Model
{
    use SoftDeletes;

    protected $fillable =[
        'user_atendimento_id',
        'intervencao_coordenacao',
        'intervencao_outros',
        'user_id',
        'designado_id'
    ];

    public function userAtendimento()
    {
        return $this->belongsTo(User::class,  'user_atendimento_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function designado()
    {
        return $this->belongsTo(User::class, 'designado_id');
    }
}
