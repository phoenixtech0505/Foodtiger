<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restoareas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('restorants');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('size')->default(4);

            $table->unsignedBigInteger('restoarea_id')->nullable();
            $table->foreign('restoarea_id')->references('id')->on('restoareas');

            $table->unsignedBigInteger('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('restorants');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('table_id')->nullable();
            //$table->foreign('table_id')->references('id')->on('tables');
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
            $table->dropColumn('table_id');
        });
        Schema::dropIfExists('tables');
        Schema::dropIfExists('restoareas');
    }
}
