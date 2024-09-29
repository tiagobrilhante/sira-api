<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserUnidade extends Model
{
    use SoftDeletes;

    protected $fillable =['user_id','unidade_id'];

    public function unidades()
    {
        return $this->hasMany( Unidade::class);
    }
    public function users()
    {
        return $this->hasMany( User::class);
    }
}
