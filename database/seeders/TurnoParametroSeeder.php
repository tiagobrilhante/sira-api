<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TurnoParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('turno_parametros')->insert([
            [
                'id' => 1,
                'horario' => 'Manhã',
                'identificador_horario' => 'NM'
            ],
            [
                'id' => 2,
                'horario' => 'Tarde',
                'identificador_horario' => 'NTA'
            ],
            [
                'id' => 3,
                'horario' => 'Noite',
                'identificador_horario' => 'NN'
            ]
        ]);
    }
}
