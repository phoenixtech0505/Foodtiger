<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForMolliePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mollie_customer_id')->nullable();
            $table->string('mollie_mandate_id')->nullable();
            $table->decimal('tax_percentage', 6, 4)->default(0); // optional
            $table->text('extra_billing_information')->nullable(); // optional
            $table->string('mollie_subscribtion_id')->nullable();
        });

        Schema::table('plan', function (Blueprint $table) {
            $table->string('mollie_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('mollie_customer_id');
        $table->dropColumn('mollie_mandate_id');
        $table->dropColumn('tax_percentage');
        $table->dropColumn('trial_ends_at');
        $table->dropColumn('extra_billing_information');
        $table->dropColumn('mollie_id');
        $table->dropColumn('mollie_subscribtion_id');
    }
}
