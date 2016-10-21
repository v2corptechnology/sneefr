<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovePlacesAndPlacesNamesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('place_names');
        Schema::drop('places');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->float('latitude', 10, 6);
            $table->float('longitude', 10, 6);
            $table->string('service_place_id');

            // Define timestamps to record dates and times of changes.
            $table->timestamps();

            // Define indices.
            $table->index(['latitude', 'longitude']);
            $table->unique('slug');
        });

        Schema::create('place_names', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('place_id');
            $table->string('language', 2);
            $table->string('name');
            $table->string('formatted_address');
            $table->json('address_components');

            // Define timestamps to record dates and times of changes.
            $table->timestamps();

            // Define foreign keys
            $table->foreign('place_id')->references('id')->on('places');
            $table->unique(['place_id', 'language']);
        });
    }
}
