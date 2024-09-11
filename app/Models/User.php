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
        'nome_guerra',
        'cpf',
        'tipo',
        'reset',
        'secao_id',
        'password',
        'posto_grad_id'
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


    public function secao()
    {
        return $this->belongsTo( Secao::class);
    }

    public function telas()
    {
        return $this->hasMany( Tela::class);
    }

    public function posto_grad()
    {
        return $this->belongsTo( PostoGrad::class);
    }

    public function getFirstNameAttribute()
    {
        return
            explode(" ", $this->nome)[0];
    }


}

