<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kid_id');
            $table->unsignedInteger('do_guardian_id');
            $table->dateTime('do_time');
            $table->unsignedInteger('pu_plan_guardian_id')->nullable();   //お迎え予定者
            $table->integer('pu_plan_hour')->nullable();    //お迎え予定時間(時)
            $table->integer('pu_plan_minute')->nullable();  //お迎え予定時間(分)
            $table->unsignedInteger('pu_guardian_id')->nullable();
            $table->dateTime('pu_time')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('kid_id')->references('id')->on('kids')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('do_guardian_id')->references('id')->on('guardians')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('pu_plan_guardian_id')->references('id')->on('guardians')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('pu_guardian_id')->references('id')->on('guardians')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
