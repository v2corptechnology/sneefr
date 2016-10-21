<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('likes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('likes', function (Blueprint $table) {

            // Set an auto-incrementing primary key.
            $table->increments('id');

            // Set a composite key for the polymorphic relationship.
            $table->unsignedInteger('likeable_id');
            $table->string('likeable_type', 255);
            $table->unsignedInteger('user_id')->index();

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }
}
