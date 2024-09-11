<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DashboardColuna extends Model
{

    use SoftDeletes;

    protected $fillable = ['tela_id', 'dashboard_linha_id', 'ordem'];

    public function linha(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DashboardLinha::class);
    }


    public function tela(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tela::class);
    }

}
