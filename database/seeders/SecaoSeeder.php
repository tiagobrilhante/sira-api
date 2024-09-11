<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SecaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('secaos')->insert([
            [
                'id' => 1,
                'nome' => 'Comando do CMA',
                'sigla' => 'Cmdo Cma',
                'secao_pai'=>1
            ],
            [
                'id' => 2,
                'nome' => 'Seção de Administração de Serviço e Controle Externo',
                'sigla' => 'E1',
                'secao_pai'=>1
            ],
            [
                'id' => 3,
                'nome' => 'Seção de Inteligência',
                'sigla' => 'E2',
                'secao_pai'=>1
            ],
            [
                'id' => 4,
                'nome' => 'Seção de Operações',
                'sigla' => 'E3',
                'secao_pai'=>1
            ],
            [
                'id' => 5,
                'nome' => 'Seção de Logística',
                'sigla' => 'E4',
                'secao_pai'=>1
            ],
            [
                'id' => 6,
                'nome' => 'Seção de Planejamento Estratégico',
                'sigla' => 'SPEI',
                'secao_pai'=>1
            ],
            [
                'id' => 7,
                'nome' => 'Assessoria de Gestão',
                'sigla' => 'Gestão',
                'secao_pai'=>1
            ],
            [
                'id' => 8,
                'nome' => 'Seção de Comunicação Social',
                'sigla' => 'E7',
                'secao_pai'=>1
            ],
            [
                'id' => 9,
                'nome' => 'Assessoria Cultural',
                'sigla' => 'AssCult',
                'secao_pai'=>1
            ],
            [
                'id' => 10,
                'nome' => 'Centro de Coordenação de Operações',
                'sigla' => 'CCOp',
                'secao_pai'=>1
            ]
        ]);
    }
}
