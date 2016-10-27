<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_tag', function (Blueprint $table) {
            $table->unsignedInteger('shop_id')->references('id')->on('shops');
            $table->unsignedInteger('tag_id')->references('id')->on('tags');

            $table->unique(['shop_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_tag');
    }
}
