<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('avatar')->default('pig.jpg')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->boolean('email_verified')->default(false);
            $table->bigInteger('facebook_id')->unsigned();
            $table->string('facebook_email')->unique()->nullable();
            $table->string('surname')->nullable();
            $table->string('given_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('locale', 2)->nullable();
            $table->boolean('verified');
            $table->json('phone')->nullable();
            $table->date('birthdate')->nullable();
            $table->text('location')->nullable();
            $table->float('lat', 10, 6)->nullable();
            $table->float('long', 10, 6)->nullable();
            $table->json('gamification_objectives')->nullable();
            $table->json('preferences')->nullable();
            $table->json('data')->nullable();
            $table->json('payment')->nullable()->default(null);
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('token')->nullable();
            $table->rememberToken();


            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
