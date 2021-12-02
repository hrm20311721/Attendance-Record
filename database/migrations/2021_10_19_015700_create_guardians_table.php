<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuardiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kid_id');
            $table->string('relation');         //続柄
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();

            $table->index('id');
            $table->index('kid_id');
            $table->index('relation');
            $table->index('name');

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
        Schema::dropIfExists('guardians');
    }
}
