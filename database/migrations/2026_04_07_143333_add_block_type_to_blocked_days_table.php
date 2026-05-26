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
        Schema::table('blocked_days', function (Blueprint $table) {
            // full = día completo, morning = mañana (turno 1), afternoon = tarde (turno 2)
            $table->string('block_type')->default('full')->after('reason');
        });
    }

    public function down()
    {
        Schema::table('blocked_days', function (Blueprint $table) {
            $table->dropColumn('block_type');
        });
    }
};
