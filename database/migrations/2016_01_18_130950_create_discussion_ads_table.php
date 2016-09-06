<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscussionAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_ads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discussion_id');
            $table->unsignedInteger('ad_id');

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->index('discussion_id');
            $table->foreign('discussion_id')->references('id')->on('discussions');
            $table->foreign('ad_id')->references('id')->on('ads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('discussion_ads');
    }
}
