<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDadosToCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('mapeamento_total_anual')->after('natureza')->default('Somatório')->nullable();
            $table->string('mapeamento_total_mensal')->after('natureza')->default('Somatório')->nullable();
            $table->string('periodicidade')->after('natureza')->default('Mensal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            //
        });
    }
}
