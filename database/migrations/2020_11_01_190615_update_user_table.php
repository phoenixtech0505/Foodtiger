<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->integer('working')->default(1);
            $table->integer('onorder')->nullable();
            $table->integer('numorders')->default(0);
            $table->integer('rejectedorders')->nullable(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lat');
            $table->dropColumn('lng');
            $table->dropColumn('working');
            $table->dropColumn('onorder');
        });
    }
}
