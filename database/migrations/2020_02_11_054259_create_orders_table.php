<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            //$table->unsignedBigInteger('client_id');
            //$table->foreign('client_id')->references('id')->on('users');
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->references('id')->on('address');

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users');

            $table->unsignedBigInteger('restorant_id');
            $table->foreign('restorant_id')->references('id')->on('restorants');

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('users')->nullable();

            //$table->string('phone');

            $table->float('delivery_price');
            $table->float('order_price');

            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            //$table->float('total_price');

            $table->string('comment', 500);
        });

        Schema::create('order_has_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('comment', 500);
        });

        Schema::create('order_has_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');

            $table->integer('qty')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_has_status');
        Schema::dropIfExists('order_has_items');
        Schema::dropIfExists('orders');
    }
}
