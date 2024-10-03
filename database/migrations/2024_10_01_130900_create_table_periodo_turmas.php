<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePeriodoTurmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodo_turmas', function (Blueprint $table) {
            $table->id();
            $table->integer('periodo')->unsigned();
            $table->bigInteger('turno_id')->unsigned()->index();
            $table->foreign('turno_id')
                ->references('id')
                ->on('turnos')->onDelete('cascade');
            $table->integer('qtd_turmas_por_periodo')->unsigned();
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
