<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableIndicadorValors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('indicador_valors', function (Blueprint $table) {
            $table->id();
            $table->integer('valor')->nullable();
            $table->integer('mes');
            $table->integer('ano');
            $table->bigInteger('indicador_id')->unsigned()->index();
            $table->foreign('indicador_id')
                ->references('id')
                ->on('indicadors');
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
        Schema::dropIfExists('indicador_valors');
    }
}
