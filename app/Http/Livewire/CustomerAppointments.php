<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\AppointmentCancelledByCustomer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerAppointments extends Component
{
    protected $listeners = ['appointment-booked' => '$refresh'];

    public function markRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function cancelAppointment($id)
    {
        $appt = Appointment::with(['service', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$appt) return;

        $setting = Setting::where('barbershop_id', auth()->user()->barbershop_id)->first();
        $noticeMinutes = ($setting->cancellation_notice ?? 2) * 60;
        
        $apptDateTime = Carbon::parse($appt->date . ' ' . $appt->start_time);
        
        if (now()->addMinutes($noticeMinutes)->greaterThan($apptDateTime)) {
            session()->flash('error', 'Es demasiado tarde para cancelar esta cita. Contáctanos directamente.');
            return;
        }

        $appt->update(['status' => 'cancelled']);

        // Notificar al barbero asignado a esta cita
        $barber = User::find($appt->barber_id);

        if ($barber) {
            $barber->notify(new AppointmentCancelledByCustomer($appt));
        }

        session()->flash('message', '✅ Cita cancelada correctamente.');
    }

    public function render()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('status', 'scheduled')
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('livewire.customer-appointments', [
            'appointments' => $appointments
        ]);
    }
}
