<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentBooked extends Notification
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
        return [
            'appointment_id' => $this->appointment->id,
            'date' => $this->appointment->date,
            'start_time' => $this->appointment->start_time,
            'message' => 'Cita agendada: ' . $this->appointment->date . ' a las ' . $this->appointment->start_time,
        ];
    }
}
