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
                    'cpf' => '512.490.302-34',
                    'nome' => 'Tiago da Silva Brilhante',
                    'nome_guerra' => 'Brilhante',
                    'password' => Hash::make('123456'),
                    'tipo' => 'Administrador',
                    'reset' => 0,
                    'secao_id' => 1,
                    'posto_grad_id' => 5
                ],
                [
                    'id' => 2,
                    'cpf' => '895.386.680-49',
                    'nome' => 'Felipe Frota da Jornada',
                    'nome_guerra' => 'Jornada',
                    'password' => Hash::make('89538668049'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 2,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 3,
                    'cpf' => '168.618.668-14',
                    'nome' => 'Alexandre Ribeiro Peixoto dos Santos',
                    'nome_guerra' => 'Peixoto dos Santos',
                    'password' => Hash::make('16861866814'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 3,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 4,
                    'cpf' => '614.545.243-91',
                    'nome' => 'Leriche Albuquerque Barros',
                    'nome_guerra' => 'Leriche',
                    'password' => Hash::make('61454524391'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 4,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 5,
                    'cpf' => '074.052.477-17',
                    'nome' => 'Robson Brito Gama',
                    'nome_guerra' => 'Gama',
                    'password' => Hash::make('07405247717'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 5,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 6,
                    'cpf' => '041.749.557-94',
                    'nome' => 'Ronaldo André Furtado',
                    'nome_guerra' => 'Furtado',
                    'password' => Hash::make('04174955794'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 8,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 7,
                    'cpf' => '849.666.538-00',
                    'nome' => 'Rui Cesar Pontes Braga',
                    'nome_guerra' => 'Pontes',
                    'password' => Hash::make('84966653800'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 9,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 8,
                    'cpf' => '201.719.288-06',
                    'nome' => 'Pierre Galdino Pietro',
                    'nome_guerra' => 'Pietro',
                    'password' => Hash::make('20171928806'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 7,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 9,
                    'cpf' => '879.949.506-63',
                    'nome' => 'Roberto Pereira Angrizani',
                    'nome_guerra' => 'Angrizani',
                    'password' => Hash::make('87994950663'),
                    'tipo' => 'Administrador',
                    'reset' => 1,
                    'secao_id' => 1,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 10,
                    'cpf' => '002.931.447-02',
                    'nome' => 'Antônio Carlos Pavão Madureira',
                    'nome_guerra' => 'Pavão',
                    'password' => Hash::make('00293144702'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 6,
                    'posto_grad_id' => 4
                ],
                [
                    'id' => 11,
                    'cpf' => '001.174.926-12',
                    'nome' => 'Allan Danilo Paiva Salazar',
                    'nome_guerra' => 'Allan',
                    'password' => Hash::make('00117492612'),
                    'tipo' => 'Usuário',
                    'reset' => 1,
                    'secao_id' => 10,
                    'posto_grad_id' => 4
                ],

                [
                    'id' => 12,
                    'cpf' => '953.573.070-32',
                    'nome' => 'Auditor de teste',
                    'nome_guerra' => 'Auditor',
                    'password' => Hash::make('123456'),
                    'tipo' => 'Auditor',
                    'reset' => 0,
                    'secao_id' => 8,
                    'posto_grad_id' => 7
                ],

                [
                    'id' => 13,
                    'cpf' => '462.131.200-68',
                    'nome' => 'Testador E1',
                    'nome_guerra' => 'T E1',
                    'password' => Hash::make('123456'),
                    'tipo' => 'Usuário',
                    'reset' => 0,
                    'secao_id' => 2,
                    'posto_grad_id' => 5
                ],
            ]
        );
    }
}
