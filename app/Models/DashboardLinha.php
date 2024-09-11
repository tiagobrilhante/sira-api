<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DashboardLinha extends Model
{

    use SoftDeletes;

    protected $fillable = ['dashboard_id', 'ordem'];

    public function dashboard(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dashboard::class);
    }

    public function colunas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DashboardColuna::class);
    }
}
