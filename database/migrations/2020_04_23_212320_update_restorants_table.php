<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRestorantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restorants', function (Blueprint $table) {
            $table->float('fee')->default(0);
            $table->float('static_fee')->default(0);
            $table->string('subdomain')->nullable()->change();
            $table->integer('radius')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restorants', function (Blueprint $table) {
            $table->dropColumn('fee');
            $table->dropColumn('static_fee');
            $table->dropColumn('radius');
        });
    }
}
