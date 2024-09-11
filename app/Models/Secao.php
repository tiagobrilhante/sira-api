<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Secao extends Model
{

    use SoftDeletes;

    protected $fillable =['nome','sigla','secao_pai'];
    protected $perPage = 99999;
    protected $appends = ['pai', 'num_categories'];

    public function users()
    {
        return $this->hasMany( User::class);
    }

    public function categoria()
    {
        return $this->hasMany( Categoria::class);
    }

    // Relacionamento para as seções filhas
    public function filhos()
    {
        return $this->hasMany(__CLASS__, 'secao_pai');
    }


    public function getPaiAttribute()
    {
        if ($this->id !== $this->secao_pai && $this->secao_pai !== null) {
            $secao = Secao::find($this->secao_pai);
            $object = (object) [
                'id' => $secao->id,
                'nome' => $secao->nome,
                'sigla' => $secao->sigla
            ];
        } else {

            $object = (object) [
                'id' => $this->id,
                'nome' => $this->nome,
                'sigla' => 'Essa é uma seção pai'
            ];

        }
        return $object;
    }
    public function getNumCategoriesAttribute()
    {
        return $this->categoria()->count();
    }
}
