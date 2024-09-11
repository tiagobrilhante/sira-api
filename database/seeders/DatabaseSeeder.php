<?php

namespace Database\Seeders;

use App\Models\Categoria;
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
             SecaoSeeder::class,
             PostoGradSeeder::class,
             UserSeeder::class,
             CategoriaSeeder::class,
             IndicadorSeeder::class,
             IndicadorValorSeeder::class,
         ]);
    }
}
