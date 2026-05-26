<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de citas a clientes 2 horas antes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $targetTime = $now->copy()->addHours(2);

        // Buscar citas que estén programadas para hoy, dentro de las próximas 2 horas
        // y a las que no se les haya enviado el recordatorio aún.
        // Además, solo procesamos citas de barberías que NO estén en el plan 'basic' (Street).
        $appointments = Appointment::with(['user', 'service', 'barbershop'])
            ->whereHas('barbershop', function($q) {
                $q->where('plan_type', '!=', 'basic');
            })
            ->where('status', 'scheduled')
            ->where('reminder_sent', false)
            ->whereNotNull('user_id') // Solo a clientes registrados que tengan email
            ->where('date', $now->toDateString())
            ->whereTime('start_time', '>', $now->toTimeString())
            ->whereTime('start_time', '<=', $targetTime->toTimeString())
            ->get();

        $count = 0;

        foreach ($appointments as $appointment) {
            if ($appointment->user && $appointment->user->email) {
                try {
                    $appointment->user->notify(new AppointmentReminder($appointment));
                    $appointment->update(['reminder_sent' => true]);
                    $count++;
                    $this->info("Recordatorio enviado a cita ID: {$appointment->id}");
                } catch (\Exception $e) {
                    Log::error("Error enviando recordatorio cita ID {$appointment->id}: " . $e->getMessage());
                    $this->error("Error enviando recordatorio cita ID {$appointment->id}");
                }
            }
        }

        $this->info("Se enviaron {$count} recordatorios exitosamente.");
        return Command::SUCCESS;
    }
}
