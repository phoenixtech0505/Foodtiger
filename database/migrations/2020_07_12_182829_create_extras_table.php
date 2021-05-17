<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->float('price');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('order_has_items', function (Blueprint $table) {
            $table->string('extras')->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extras');
        Schema::table('order_has_items', function (Blueprint $table) {
            $table->dropColumn('extras');
        });
    }
}
