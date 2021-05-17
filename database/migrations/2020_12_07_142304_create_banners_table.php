<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('img')->default('');
            $table->integer('type')->default(0)->comment('0 - Vendor, 1 - Blog');
            $table->unsignedBigInteger('vendor_id')->nullable()->default(null);
            $table->foreign('vendor_id')->references('id')->on('restorants');
            $table->unsignedBigInteger('page_id')->nullable()->default(null);
            $table->foreign('page_id')->references('id')->on('pages');
            $table->date('active_from');
            $table->date('active_to');
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
        Schema::dropIfExists('banners');
    }
}
