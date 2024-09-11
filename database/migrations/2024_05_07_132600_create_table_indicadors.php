<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableIndicadors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('indicadors', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tendencia')->nullable();
            $table->integer('objetivo')->nullable();
            $table->integer('green')->nullable();
            $table->integer('yellow_1')->nullable();
            $table->integer('yellow_2')->nullable();
            $table->integer('red')->nullable();
            $table->boolean('meta')->default(true);
            $table->bigInteger('categoria_id')->unsigned()->index();
            $table->foreign('categoria_id')
                ->references('id')
                ->on('categorias');
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
        Schema::dropIfExists('indicadors');
    }
}
