<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRestaurantsForDeliveryPickup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restorants', function (Blueprint $table) {
            $table->integer('can_pickup')->default(1);
            $table->integer('can_deliver')->default(1);
            $table->integer('self_deliver')->default(0);
            $table->integer('free_deliver')->default(0);
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
            $table->dropColumn('can_pickup');
            $table->dropColumn('can_deliver');
            $table->dropColumn('self_deliver');
            $table->dropColumn('free_deliver');
        });
    }
}
