<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Indicador;


class IndicadorValorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $indicadores = Indicador::all();
        $anos = [2022, 2023, 2024];
        $meses = [
            2022 => range(1, 12),
            2023 => range(1, 12),
            2024 => range(1, 5)
        ];

        foreach ($indicadores as $indicador) {
            foreach ($anos as $ano) {
                foreach ($meses[$ano] as $mes) {
                    DB::table('indicador_valors')->insert([
                        'valor' => $faker->randomFloat(2, 0, 100),
                        'mes' => $mes,
                        'ano' => $ano,
                        'indicador_id' => $indicador->id,
                        'atualizado' => $faker->dateTimeBetween("$ano-$mes-01", "$ano-$mes-28"),
                    ]);
                }
            }
        }
    }
}
