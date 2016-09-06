<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('slug', 120);
            $table->json('data');

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // Define indices.
            $table->unique('slug');
            $table->index(['slug', 'deleted_at']);
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('ads', function (Blueprint $table) {
            $table->foreign('shop_id', 'ads_shop_id_foreign')->references('id')->on('shops');
        });

        Schema::table('discussions', function (Blueprint $table) {
            $table->foreign('shop_id', 'discussions_shop_id_foreign')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropForeign('ads_shop_id_foreign');
        });

        Schema::table('discussions', function (Blueprint $table) {
            $table->dropForeign('discussions_shop_id_foreign');
        });

        Schema::drop('shops');
    }
}
