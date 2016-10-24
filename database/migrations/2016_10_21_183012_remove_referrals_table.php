<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('referrals');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
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
}
