<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PostoGradSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posto_grads')->insert([
            [
                'id' => 1,
                'pg' => 'Gen Ex',
                'antiguidade' => 1
            ],
            [
                'id' => 2,
                'pg' => 'Gen Div',
                'antiguidade' => 2
            ],
            [
                'id' => 3,
                'pg' => 'Gen Bda',
                'antiguidade' => 3
            ],
            [
                'id' => 4,
                'pg' => 'Cel',
                'antiguidade' => 4
            ],
            [
                'id' => 5,
                'pg' => 'TC',
                'antiguidade' => 5
            ],
            [
                'id' => 6,
                'pg' => 'Maj',
                'antiguidade' => 6
            ],
            [
                'id' => 7,
                'pg' => 'Cap',
                'antiguidade' => 7
            ],
            [
                'id' => 8,
                'pg' => '1º Ten',
                'antiguidade' => 8
            ],
            [
                'id' => 9,
                'pg' => '2º Ten',
                'antiguidade' => 9
            ],
            [
                'id' => 10,
                'pg' => 'Asp',
                'antiguidade' => 10
            ],
            [
                'id' => 11,
                'pg' => 'ST',
                'antiguidade' => 11
            ],
            [
                'id' => 12,
                'pg' => '1º Sgt',
                'antiguidade' => 12
            ],
            [
                'id' => 13,
                'pg' => '2º Sgt',
                'antiguidade' => 13
            ],
            [
                'id' => 14,
                'pg' => '3º Sgt',
                'antiguidade' => 14
            ],
            [
                'id' => 15,
                'pg' => 'Cb',
                'antiguidade' => 15
            ],
            [
                'id' => 16,
                'pg' => 'Sd',
                'antiguidade' => 16
            ],
            [
                'id' => 17,
                'pg' => 'SC',
                'antiguidade' => 17
            ],


        ]);
    }
}
