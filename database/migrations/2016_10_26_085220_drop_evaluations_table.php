<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('evaluations');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            // add columns
            $table->integer('ad_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->morphs('evaluated');
            $table->enum('status',
                ['valid', 'waiting', 'rejected', 'forced'])->default('waiting');
            $table->string('value',1);
            $table->text('body')->nullable();

            // Define timestamps to record dates and times of changes.
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            // add foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('ad_id')->references('id')->on('ads');
        });
    }
}
