<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dashboard extends Model
{

    use SoftDeletes;

    protected $fillable =['nome','destaque','ativo', 'hash', 'user_id'];
    public function user()
    {
        return $this->belongsTo( User::class);
    }

    public function linhas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany( DashboardLinha::class);
    }
}
