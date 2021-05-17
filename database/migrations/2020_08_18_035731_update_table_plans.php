<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTablePlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan', function (Blueprint $table) {
            $table->string('description', 555)->default('');
            $table->string('features', 555)->default('');
            $table->integer('limit_views')->default(0)->comment('0 is unlimited');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('features');
            $table->dropColumn('limit_views');
        });
    }
}
