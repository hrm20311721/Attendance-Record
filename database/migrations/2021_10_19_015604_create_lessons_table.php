<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kid_id');
            $table->string('name');         //習い事名
            $table->integer('schedule');    //曜日を番号で指定
            $table->unsignedInteger('pu_plan_guardian_id'); //お迎え予定の人
            $table->integer('pu_hour');     //お迎えの時間(時)
            $table->integer('pu_minute');   //お迎えの時間(分)
            $table->softDeletes();
            $table->timestamps();

            $table->index('id');
            $table->index('kid_id');
            $table->index('name');
            $table->index('schedule');

            $table->foreign('kid_id')->references('id')->on('kids')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
