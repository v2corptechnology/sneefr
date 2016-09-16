<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Create or remove a ‘likes’ table to handle likes of
 * models via Eloquent polymorphic relationships.
 */
class CreateLikesTable extends Migration
{
    /**
     * Creates the table and its indices.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function(Blueprint $table) {

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

    /**
     * Remove the table and its indices.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('likes');
    }
}
