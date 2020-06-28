<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->bigInteger('id_course')->unsigned();
            $table->bigInteger('id_user')->unsigned();
            $table->bigInteger('id_topic')->unsigned();
            $table->double('grade', 8, 2);

            $table->primary(['id_course', 'id_user', 'id_topic']);

            $table->foreign('id_course')->references('id')->on('courses');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_topic')->references('id')->on('topics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grades');
    }
}
