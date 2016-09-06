<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscussionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('discussion_users');
    }
}
