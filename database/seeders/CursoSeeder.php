<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cursos')->insert([
            [
                'id' => 1,
                'nome' => 'Ciência da Computação',
                'codigo' => '079',
                'qtd_periodos_possiveis' => 8,
                'unidade_id' => 1
            ],
            [
                'id' => 2,
                'nome' => 'Direito',
                'codigo' => '024',
                'qtd_periodos_possiveis' => 8,
                'unidade_id' => 1
            ],
            [
                'id' => 3,
                'nome' => 'Enfermagem',
                'codigo' => '012',
                'qtd_periodos_possiveis' => 8,
                'unidade_id' => 1
            ]
        ]);
    }
}
