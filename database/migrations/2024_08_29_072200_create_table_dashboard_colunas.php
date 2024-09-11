<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDashboardColunas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('dashboard_colunas', function (Blueprint $table) {
            $table->id();
            $table->integer('ordem');


            $table->bigInteger('tela_id')->unsigned()->index();
            $table->foreign('tela_id')
                ->references('id')
                ->on('telas');

            $table->bigInteger('dashboard_linha_id')->unsigned()->index();
            $table->foreign('dashboard_linha_id')
                ->references('id')
                ->on('dashboard_linhas')
                ->onDelete('cascade');

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
        Schema::dropIfExists('dashboard_colunas');
    }
}
