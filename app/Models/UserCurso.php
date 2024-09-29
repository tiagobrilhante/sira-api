<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCurso extends Model
{
    use SoftDeletes;

    protected $table = 'user_curso';

    protected $fillable =['user_id','curso_id'];

    public function curso()
    {
        return $this->belongsTo( Curso::class, 'curso_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo( User::class, 'user_id', 'id');
    }
}
