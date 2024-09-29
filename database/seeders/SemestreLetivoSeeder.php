<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SemestreLetivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('semestre_letivos')->insert([
            [
                'id' => 1,
                'codigo' => '24.2',
                'curso_id' => 1
            ],
            [
                'id' => 2,
                'codigo' => '24.1',
                'curso_id' => 1
            ],
            [
                'id' => 3,
                'codigo' => '23.1',
                'curso_id' => 3
            ],
            [
                'id' => 4,
                'codigo' => '23.2',
                'curso_id' => 2
            ]
        ]);
    }
}
