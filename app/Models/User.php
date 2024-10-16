<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    use SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'matricula',
        'telefone',
        'email',
        'tipo',
        'reset',
        'password',
    ];

    protected $appends = ['firstName'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getFirstNameAttribute()
    {
        return
            explode(" ", $this->nome)[0];
    }


    public function cursos()
    {
        return $this->hasMany( UserCurso::class, 'user_id', 'id');
    }

    public function alunoVinculos()
    {
        return $this->hasMany( UserAlunoVinculo::class, 'user_id', 'id');
    }


}

