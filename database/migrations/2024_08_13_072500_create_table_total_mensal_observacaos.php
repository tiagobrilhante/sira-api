<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTotalMensalObservacaos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('total_mensal_observacaos', function (Blueprint $table) {
            $table->id();
            $table->longText('observacao')->nullable();
            $table->longText('resp');
            $table->integer('mes');
            $table->integer('ano');
            $table->bigInteger('categoria_id')->unsigned()->index();
            $table->foreign('categoria_id')
                ->references('id')
                ->on('categorias');
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
        Schema::dropIfExists('total_mensal_observacaos');
    }
}
