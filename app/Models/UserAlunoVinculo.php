<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAlunoVinculo extends Model
{
    use SoftDeletes;

    protected $table = 'user_aluno_vinculo';

    protected $fillable =['user_id','codigo_vinculo', 'semestre_letivo'];

    public function user()
    {
        return $this->belongsTo( User::class, 'user_id', 'id');
    }
}
