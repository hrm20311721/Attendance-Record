<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('grade_id')->comment('組');
            $table->timestamps();
            $table->softDeletes();//退園した日付

            $table->index('id');
            $table->index('name');
            $table->index('grade_id');

            $table->foreign('grade_id')->references('id')->on('grades')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kids');
    }
}
