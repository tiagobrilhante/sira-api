<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unidades')->insert([
            [
                'id' => 1,
                'nome' => 'Plaza',
                'prefixo' => 'Spl'
            ],
            [
                'id' => 2,
                'nome' => 'Centro',
                'prefixo' => 'Cnt'
            ]
        ]);
    }
}
