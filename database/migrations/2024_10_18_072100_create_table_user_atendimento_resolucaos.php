<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserAtendimentoResolucaos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()

    {
        Schema::create('user_atendimento_resolucaos', function (Blueprint $table) {

            $table->id();
            $table->longText('intervencao_coordenacao');
            $table->longText('intervencao_outros')->nullable();

            $table->bigInteger('user_atendimento_id')->unsigned()->index();
            $table->foreign('user_atendimento_id')
                ->references('id')
                ->on('user_atendimentos')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')->onDelete('cascade');


            $table->bigInteger('designado_id')->unsigned()->index()->nullable();
            $table->foreign('designado_id')
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
        Schema::dropIfExists('user_atendimento_resolucaos');
    }
}
