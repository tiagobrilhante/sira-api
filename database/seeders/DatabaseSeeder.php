<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\PeriodoTurma;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             UnidadeSeeder::class,
             CursoSeeder::class,
             SemestreLetivoSeeder::class,
             TurnoParametroSeeder::class,
             TurnoSeeder::class,
             UserSeeder::class,
             PeriodoTurmaSeeder::class,
         ]);
    }
}
