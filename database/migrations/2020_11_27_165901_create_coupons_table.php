<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code', 8)->unique();
            $table->unsignedBigInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('restorants');
            $table->integer('type')->default(1)->comment('0 - Fixed, 1 - Percentage');
            $table->float('price');
            $table->date('active_from');
            $table->date('active_to');
            $table->integer('limit_to_num_uses');
            $table->integer('used_count')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('code');
            $table->dropColumn('restaurant_id');
            $table->dropColumn('type');
            $table->dropColumn('price');
            $table->dropColumn('active_from');
            $table->dropColumn('active_to');
            $table->dropColumn('limit_to_num_uses');
            $table->dropColumn('used_count');
        });
    }
}
