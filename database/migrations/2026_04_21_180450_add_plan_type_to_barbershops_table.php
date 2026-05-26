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
            $table->enum('plan_type', ['basic', 'pro', 'elite'])->default('basic')->after('subscription_status');
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
            $table->dropColumn('plan_type');
        });
    }
};
