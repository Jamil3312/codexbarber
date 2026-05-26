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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_superadmin')->default(false)->after('is_barber');
            $table->foreignId('barbershop_id')->nullable()->constrained()->nullOnDelete()->after('id');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('barbershop_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('barbershop_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('barbershop_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['barbershop_id']);
            $table->dropColumn(['barbershop_id', 'is_superadmin']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['barbershop_id']);
            $table->dropColumn('barbershop_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['barbershop_id']);
            $table->dropColumn('barbershop_id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign(['barbershop_id']);
            $table->dropColumn('barbershop_id');
        });
    }
};
