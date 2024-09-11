<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tela extends Model
{

    use SoftDeletes;

    protected $fillable =['nome', 'hash','objetoPesquisa', 'user_id'];
    public function user()
    {
        return $this->belongsTo( User::class);
    }

    public function coluna(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DashboardColuna::class);
    }
}
