<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname');
            $table->string('name');
            $table->integer('age');
            $table->integer('room_id')->unsigned()->nullable();

            $table->foreign('room_id')->references('id')->on('rooms');

        });

        // Schema::table('guests', function(Blueprint $table) {
        //     $table->foreign('room_id')->references('id')->on('rooms');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('guests');

    }
}
