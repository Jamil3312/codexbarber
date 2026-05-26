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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('slot_duration')->default(45); // in minutes
            $table->time('start_time_1')->default('09:00:00');
            $table->time('end_time_1')->default('13:00:00');
            $table->time('start_time_2')->nullable()->default('15:00:00'); // optional second shift
            $table->time('end_time_2')->nullable()->default('19:00:00');
            $table->integer('cancellation_notice')->default(2); // hours before appointment
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
        Schema::dropIfExists('settings');
    }
};
