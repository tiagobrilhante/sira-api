<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TurnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('turnos')->insert([
            [
                'id' => 1,
                'turno_parametro_id' => 1,
                'semestre_letivo_id' => 1,
                'qtd_turmas' => 1
            ],
            [
                'id' => 2,
                'turno_parametro_id' => 2,
                'semestre_letivo_id' => 2,
                'qtd_turmas' => 3
            ],
            [
                'id' => 3,
                'turno_parametro_id' => 1,
                'semestre_letivo_id' => 2,
                'qtd_turmas' => 2
            ],
            [
                'id' => 4,
                'turno_parametro_id' => 2,
                'semestre_letivo_id' => 3,
                'qtd_turmas' => 4
            ],
            [
                'id' => 5,
                'turno_parametro_id' => 2,
                'semestre_letivo_id' => 1,
                'qtd_turmas' => 3
            ],
            [
                'id' => 6,
                'turno_parametro_id' => 3,
                'semestre_letivo_id' => 4,
                'qtd_turmas' => 2
            ],
        ]);
    }
}
