<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                [
                    'id' => 1,
                    'nome' => 'Tiago da Silva Brilhante',
                    'matricula' => '123456',
                    'telefone' => '(92) 9 9155-4494',
                    'email' => 'tiagobrilhante@gmail.com',
                    'tipo' => 'Administrador Geral',
                    'reset' => 0,
                    'password' => Hash::make('123456'),
                ],
                [
                    'id' => 2,
                    'nome' => 'Aluno de teste',
                    'matricula' => '654321',
                    'telefone' => '(92) 9 9155-4493',
                    'email' => 'aluno@gmail.com',
                    'tipo' => 'Aluno',
                    'reset' => 0,
                    'password' => Hash::make('123456'),
                ],
            ]
        );
    }
}
