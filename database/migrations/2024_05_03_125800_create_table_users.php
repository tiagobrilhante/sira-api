<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('cpf')->unique();
            $table->string('nome');
            $table->string('nome_guerra');
            $table->string('password');
            $table->string('tipo')->default('Usuário');
            $table->boolean('reset')->default(false);

            $table->bigInteger('secao_id')->unsigned()->index();
            $table->foreign('secao_id')
                ->references('id')
                ->on('secaos');

            $table->bigInteger('posto_grad_id')->unsigned()->index();
            $table->foreign('posto_grad_id')
                ->references('id')
                ->on('posto_grads');

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
        Schema::dropIfExists('users');
    }
}
