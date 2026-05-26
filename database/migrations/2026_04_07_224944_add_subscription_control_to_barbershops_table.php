<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('barbershops', function (Blueprint $table) {
            // Fecha en que inició el período (definida manualmente por admin)
            $table->date('subscription_starts_at')->nullable()->after('paid_until');
            // Tipo de suscripción para calcular paid_until automáticamente
            $table->string('subscription_type')->default('monthly')->after('subscription_starts_at'); // monthly | yearly
            // Días de gracia tras vencer antes de auto-suspender
            $table->unsignedTinyInteger('grace_days')->default(3)->after('subscription_type');
            // Campos de bloqueo manual (distinto de suspended por vencimiento)
            $table->string('block_reason')->nullable()->after('grace_days');
            $table->timestamp('blocked_at')->nullable()->after('block_reason');
            // Notas internas del admin
            $table->text('admin_notes')->nullable()->after('blocked_at');
        });

        // Añadir 'blocked' como valor válido en subscription_status
        // (MySQL ENUM sería la forma estricta, pero usamos string para flexibilidad)
        // Los valores posibles: active | suspended | blocked
    }

    public function down()
    {
        Schema::table('barbershops', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_starts_at',
                'subscription_type',
                'grace_days',
                'block_reason',
                'blocked_at',
                'admin_notes',
            ]);
        });
    }
};
