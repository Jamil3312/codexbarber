<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentCancelledByBlock extends Notification
{
    use Queueable;

    public $appointment;
    public $blockType;
    public $reason;

    public function __construct(Appointment $appointment, string $blockType, ?string $reason = null)
    {
        $this->appointment = $appointment;
        $this->blockType   = $blockType;
        $this->reason      = $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $date    = \Carbon\Carbon::parse($this->appointment->date)->translatedFormat('d \d\e F');
        $time    = \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i');
        $service = $this->appointment->service->name ?? 'tu servicio';

        $periodLabel = match($this->blockType) {
            'morning'   => 'de la mañana',
            'afternoon' => 'de la tarde',
            default     => 'del día',
        };

        $reasonText = $this->reason ? " Motivo: {$this->reason}." : '';

        return [
            'appointment_id' => $this->appointment->id,
            'type'           => 'cancelled_by_block',
            'message'        => "🔔 Tu cita de $service del $date a las $time fue cancelada porque la barbería cerrará el turno $periodLabel.$reasonText Puedes reagendar cuando gustes.",
        ];
    }
}
