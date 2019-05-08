<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_id')->unsigned();
            $table->date('arrival');
            $table->date('departure');
            $table->integer('guest_id')->unsigned();

            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('guest_id')->references('id')->on('guests');
        });

        // Schema::table('bookings', function (Blueprint $table) {
        //     $table->foreign('room_id')->references('id')->on('rooms');
        //     $table->foreign('guests')->references('id')->on('guests');
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
}
