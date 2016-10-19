<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('notifications');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('notifiable_type');
            $table->integer('notifiable_id')->unsigned();
            $table->boolean('is_special')->default(false);

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('read_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
