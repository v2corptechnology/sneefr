<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_tag', function (Blueprint $table) {
            $table->unsignedInteger('item_id')->references('id')->on('ads');
            $table->unsignedInteger('tag_id')->references('id')->on('tags');

            $table->unique(['item_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_tag');
    }
}
