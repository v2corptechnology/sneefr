<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPasswordAvatarFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        if(!env('DB_CONNECTION') == 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar')->default('pig.jpg')->nullable()->after('id');
                $table->string('password')->nullable()->after('email');
            });

            DB::statement(" alter table `users` MODIFY `facebook_id` bigint unsigned null ");
            DB::statement(" alter table `users` MODIFY `surname` varchar(255) null ");
            DB::statement(" alter table `users` MODIFY `given_name` varchar(255) null ");
            DB::statement(" alter table `users` MODIFY `preferences` json null ");
            DB::statement(" alter table `users` MODIFY `token` varchar(255) null ");
        }
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        if(!env('DB_CONNECTION') == 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('avatar');
                $table->dropColumn('password');
            });
        }
        */
    }
}
