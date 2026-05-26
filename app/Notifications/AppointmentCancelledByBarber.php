<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentCancelledByBarber extends Notification
{
    use Queueable;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $date = \Carbon\Carbon::parse($this->appointment->date)->translatedFormat('d \d\e F');
        $time = \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i');
        $service = $this->appointment->service->name ?? 'tu cita';

        return [
            'appointment_id' => $this->appointment->id,
            'type'           => 'cancelled_by_barber',
            'message'        => "❌ Tu cita de $service del $date a las $time fue cancelada por la barbería. Puedes reagendar cuando gustes.",
        ];
    }
}
