<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDiscussionsAndMessagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('discussion_ads');
        Schema::drop('discussion_users');
        Schema::drop('messages');
        Schema::drop('discussions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id')->nullable();

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discussion_id');
            $table->integer('from_user_id')->unsigned();
            $table->integer('to_user_id')->unsigned();
            $table->text('body');

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('from_user_id')->references('id')->on('users');
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('discussion_id')->references('id')->on('discussions');
        });

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
        
        Schema::create('discussion_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discussion_id');
            $table->unsignedInteger('user_id');

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('discussion_id')->references('id')->on('discussions');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
