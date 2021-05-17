<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserAddPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable();
            //$table->foreign('plan_id')->references('id')->on('plan');
            $table->string('plan_status')->default('');
            $table->string('cancel_url', 555)->default('');
            $table->string('update_url', 555)->default('');
            $table->string('checkout_id', 555)->default('');
            $table->string('subscription_plan_id', 555)->default('');
            $table->string('stripe_account')->default('');
            $table->string('birth_date')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
