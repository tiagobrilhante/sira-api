<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PeriodoTurmaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('periodo_turmas')->insert([
            [
                'id' => 1,
                'turno_id' => 1,
                'periodo' => 2,
                'qtd_turmas_por_periodo' => 1
            ],
            [
                'id' => 2,
                'turno_id' => 2,
                'periodo' => 3,
                'qtd_turmas_por_periodo' => 3
            ],
            [
                'id' => 3,
                'turno_id' => 3,
                'periodo' => 1,
                'qtd_turmas_por_periodo' => 2
            ],
            [
                'id' => 4,
                'turno_id' => 4,
                'periodo' => 5,
                'qtd_turmas_por_periodo' => 4
            ],
            [
                'id' => 5,
                'periodo' => 6,
                'turno_id' => 5,
                'qtd_turmas_por_periodo' => 3
            ],
            [
                'id' => 6,
                'periodo' => 7,
                'turno_id' => 6,
                'qtd_turmas_por_periodo' => 2
            ],
            [
                'id' => 7,
                'periodo' => 3,
                'turno_id' => 5,
                'qtd_turmas_por_periodo' => 2
            ],
        ]);
    }
}
