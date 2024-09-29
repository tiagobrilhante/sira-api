<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSemestreLetivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semestre_letivos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->bigInteger('curso_id')->unsigned()->index();
            $table->foreign('curso_id')
                ->references('id')
                ->on('cursos')->onDelete('cascade');
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
        Schema::dropIfExists('semestre_letivos');
    }
}
