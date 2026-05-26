<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentCancelledByCustomer extends Notification
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
        $service = $this->appointment->service->name ?? 'un servicio';
        $clientName = $this->appointment->walkin_name
            ?? ($this->appointment->user->name ?? 'Un cliente');

        return [
            'appointment_id' => $this->appointment->id,
            'type'           => 'cancelled_by_customer',
            'message'        => "⚠️ $clientName canceló su cita de $service del $date a las $time. El horario está libre nuevamente.",
        ];
    }
}
