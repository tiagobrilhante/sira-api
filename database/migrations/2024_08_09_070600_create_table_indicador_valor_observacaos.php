<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableIndicadorValorObservacaos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('indicador_valor_observacaos', function (Blueprint $table) {
            $table->id();
            $table->longText('observacao')->nullable();
            $table->longText('resp');
            $table->bigInteger('indicador_valor_id')->unsigned()->index();
            $table->foreign('indicador_valor_id')
                ->references('id')
                ->on('indicador_valors');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

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
        Schema::dropIfExists('indicador_valor_observacaos');
    }
}
