<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForPaystackSub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('paystack_subscribtion_id')->nullable();
        });

        Schema::table('plan', function (Blueprint $table) {
            $table->string('paystack_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('paystack_subscribtion_id');
        $table->dropColumn('paystack_id');
    }
}
