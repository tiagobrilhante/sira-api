<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTurnos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('turno_parametro_id')->unsigned()->index();
            $table->foreign('turno_parametro_id')
                ->references('id')
                ->on('turno_parametros')->onDelete('cascade');
            $table->integer('qtd_turmas')->unsigned();
            $table->bigInteger('semestre_letivo_id')->unsigned()->index();
            $table->foreign('semestre_letivo_id')
                ->references('id')
                ->on('semestre_letivos')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turnos');
    }
}
