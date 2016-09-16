<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->unsignedInteger('referent_user_id');
            $table->unsignedInteger('referred_user_id');

            // Define timestamps to record dates and times of changes.
            $table->timestamps();

            // Foreign keys and indices
            $table->unique(['referent_user_id', 'referred_user_id']);
            $table->foreign('referent_user_id')->references('id')->on('users');
            $table->foreign('referred_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('referrals');
    }
}
