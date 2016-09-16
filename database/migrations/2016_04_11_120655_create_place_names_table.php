<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('place_names');
    }
}
