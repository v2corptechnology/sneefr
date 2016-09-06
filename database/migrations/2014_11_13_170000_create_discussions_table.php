<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscussionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id')->nullable();

            // Define timestamps to record dates and times of changes.
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
        Schema::drop('discussions');
    }

}
