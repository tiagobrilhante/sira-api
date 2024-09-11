<?php

namespace App\Models;

class SecaoImpacto
{
    public $secao;
    public $filhos;
    public $users;
    public $categorias;
    public $indicadores;
    public $indicadorValor;

    public function __construct($secao, $filhos, $users, $categorias, $indicadores, $indicadorValor)
    {
        $this->secao = $secao;
        $this->filhos = $filhos;
        $this->users = $users;
        $this->categorias = $categorias;
        $this->indicadores = $indicadores;
        $this->indicadorValor = $indicadorValor;
    }

    public function jsonSerialize()
    {
        return [
            'secao' => $this->secao,
            'filhos' => $this->filhos,
            'users' => $this->users,
            'categorias' => $this->categorias,
            'indicadores' => $this->indicadores,
            'indicadorValor' => $this->indicadorValor,
        ];
    }
}
