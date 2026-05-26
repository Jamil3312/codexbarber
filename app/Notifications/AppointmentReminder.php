<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentReminder extends Notification
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

        return (new MailMessage)
                    ->subject("Recordatorio: Tu cita es hoy a las {$time}")
                    ->greeting("¡Hola " . ($this->appointment->user->name ?? $this->appointment->walkin_name) . "!")
                    ->line("Te recordamos que tienes una cita programada para hoy en **{$barbershopName}**.")
                    ->line("**Hora:** {$time}")
                    ->line("**Servicio:** " . ($this->appointment->service->name ?? 'Cita General'))
                    ->line("Te pedimos amablemente llegar con 5 minutos de anticipación para brindarte el mejor servicio posible.")
                    ->action('Ver Detalles de la Cita', url("/b/" . ($this->appointment->barbershop->slug ?? '')))
                    ->line('¡Te esperamos!');
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
