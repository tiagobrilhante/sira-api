<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserAtendimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()

    {
        Schema::create('user_atendimentos', function (Blueprint $table) {

            $table->id();
            $table->longText('descricao');
            $table->string('periodo_letivo');
            $table->string('codigo_geral');
            $table->string('status');
            $table->dateTime('data_solicitacao');
            $table->string('data_solucao')->nullable();

            $table->bigInteger('curso_id')->unsigned()->index();
            $table->foreign('curso_id')
                ->references('id')
                ->on('cursos')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public
    function down()
    {
        Schema::dropIfExists('user_atendimentos');
    }
}
