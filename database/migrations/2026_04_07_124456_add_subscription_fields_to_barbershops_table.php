<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barbershops', function (Blueprint $table) {
            $table->string('subscription_status')->default('active'); // active, suspended
            $table->date('paid_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barbershops', function (Blueprint $table) {
            $table->dropColumn(['subscription_status', 'paid_until']);
        });
    }
};
