<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostoGrad extends Model
{

    use SoftDeletes;

    protected $fillable =['pg'];
    public function users()
    {
        return $this->hasMany( User::class);
    }
}
