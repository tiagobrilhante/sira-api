<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDashboards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->longText('nome');
            $table->longText('hash');
            $table->boolean('ativo');
            $table->boolean('destaque');
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
        Schema::dropIfExists('dashboards');
    }
}
