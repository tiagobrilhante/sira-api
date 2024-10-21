<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAtendimento extends Model
{
    use SoftDeletes;

    protected $fillable =[
        'periodo_letivo',
        'descricao',
        'codigo_geral',
        'status',
        'data_solicitacao',
        'data_solucao',
        'user_id',
        'curso_id'
    ];

    public function aluno()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function userAtendimentoResolucao()
    {
        return $this->hasOne(UserAtendimentoResolucao::class);
    }
}
