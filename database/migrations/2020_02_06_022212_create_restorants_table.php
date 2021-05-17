<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestorantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restorants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name')->unique();
            $table->string('subdomain')->unique();
            $table->string('logo')->default('');
            $table->string('cover')->default('');
            $table->boolean('active')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('lat');
            $table->string('lng');
            $table->string('address');
            $table->string('phone');
            $table->string('minimum')->default(0);
            $table->string('description', 500)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restorants');
    }
}
