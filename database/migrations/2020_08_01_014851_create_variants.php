<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('has_variants')->default(0);
            $table->float('vat')->nullable();
        });

        Schema::table('extras', function (Blueprint $table) {
            $table->integer('extra_for_all_variants')->default(1);
        });

        Schema::table('order_has_items', function (Blueprint $table) {
            $table->float('vat')->default(0)->nullable();
            $table->float('vatvalue')->default(0)->nullable();
            $table->float('variant_price')->nullable();
            $table->string('variant_name')->default('');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->float('vatvalue')->default(0)->nullable();
            $table->float('payment_processor_fee')->default(0);
        });

        Schema::create('options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->string('name');
            $table->string('options', 500);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('price');
            $table->string('options', 500);
            $table->string('image');
            $table->integer('qty')->default(0);
            $table->integer('enable_qty')->default(0);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('variants_has_extras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('variant_id');
            $table->foreign('variant_id')->references('id')->on('variants');

            $table->unsignedBigInteger('extra_id');
            $table->foreign('extra_id')->references('id')->on('extras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variants_has_extras');

        Schema::dropIfExists('variants');

        Schema::dropIfExists('options');

        Schema::table('extras', function (Blueprint $table) {
            $table->dropColumn('extra_for_all_variants');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('vatvalue');
        });

        Schema::table('order_has_items', function (Blueprint $table) {
            $table->dropColumn('vat');
            $table->dropColumn('vatvalue');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('has_variants');
        });
    }
}
