<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDashboardLinhas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('dashboard_linhas', function (Blueprint $table) {
            $table->id();

            $table->integer('ordem');

            $table->bigInteger('dashboard_id')->unsigned()->index();
            $table->foreign('dashboard_id')
                ->references('id')
                ->on('dashboards')
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
        Schema::dropIfExists('dashboard_linhas');
    }
}
