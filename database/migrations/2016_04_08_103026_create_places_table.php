<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('places');
    }
}
