<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $barbershopName = $this->appointment->barbershop->name ?? 'Nuestra Barbería';
        $time = Carbon::parse($this->appointment->start_time)->format('h:i A');
        $clientName = $this->appointment->user->name ?? $this->appointment->walkin_name ?? 'Cliente';
        $serviceName = $this->appointment->service->name ?? 'Cita General';
        $url = url("/b/" . ($this->appointment->barbershop->slug ?? ''));

        return (new MailMessage)
                    ->subject("Recordatorio: Tu cita es hoy a las {$time}")
                    ->view('emails.appointment_reminder', [
                        'barbershopName' => $barbershopName,
                        'time' => $time,
                        'clientName' => $clientName,
                        'serviceName' => $serviceName,
                        'url' => $url
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
