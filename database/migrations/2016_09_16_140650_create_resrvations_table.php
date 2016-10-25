<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResrvationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_id')->unsigned();
            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone');
            $table->string('country');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights');
            $table->integer('room_type_id')->unsigned();
            $table->foreign('room_type_id')->references('id')->on('room_types');
            $table->integer('persons');
            $table->double('price',5,2);
            $table->double('total_price',5,2);
            $table->boolean('breakfast');
            $table->boolean('deposit');
            $table->double('deposit_amount',5,2);
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('status_types');
            $table->integer('channel_id')->unsigned();
            $table->foreign('channel_id')->references('id')->on('channels');
            $table->longText('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservations');
    }
}
