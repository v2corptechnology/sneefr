<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->morphs('followable');

            // Define timestamps to record dates and times of changes.
            $table->timestamps();

            // Define foreign keys
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('followables');
    }
}
