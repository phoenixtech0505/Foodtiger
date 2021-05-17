<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->string('apartment')->nullable();
            $table->string('intercom')->nullable();
            $table->string('floor')->nullable();
            $table->string('entry')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->dropColumn('apartment');
            $table->dropColumn('intercom');
            $table->dropColumn('floor');
            $table->dropColumn('entry');
        });
    }
}
