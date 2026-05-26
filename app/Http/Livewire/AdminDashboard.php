<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Sale;
use App\Notifications\AppointmentCompleted;
use App\Notifications\AppointmentCancelledByBarber;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $appointments;
    public $activeTab = 'upcoming'; // 'upcoming' or 'completed'
    public $upcomingFilter = 'today'; // 'today', 'month', or 'all'
    public $dailyServices = 0;
    public $dailySales = 0;
    public $dailyEarnings = 0;
    
    public $monthlyServices = 0;
    public $monthlySales = 0;
    public $monthlyEarnings = 0;

    protected $listeners = ['appointment-booked' => 'refreshData'];

    public function mount()
    {
        if (!Auth::user()->is_barber) {
            abort(403, 'Acceso Denegado');
        }

        $this->refreshData();
    }

    public function refreshData()
    {
        $this->loadStats();
        $this->loadAppointments();
    }

    public function updatedUpcomingFilter()
    {
        $this->loadAppointments();
    }

    public function setActiveTab($tab)
    {
        // upcoming = Gestión, completed = Finanzas, agenda = Config. Agenda
        if (in_array($tab, ['upcoming', 'completed', 'agenda'])) {
            $this->activeTab = $tab;
            $this->loadAppointments();
        }
    }

    public function loadStats()
    {
        $barbershopId = auth()->user()->barbershop_id;
        $userId = auth()->id();
        $isOwner = auth()->user()->is_owner;

        $baseQuery = Appointment::where('barbershop_id', $barbershopId)
            ->where('status', 'completed');
            
        if (!$isOwner) {
            $baseQuery->where('barber_id', $userId);
        }

        // --- INGRESOS POR SERVICIOS ---
        // Ganancias de hoy (Citas)
        $this->dailyServices = (clone $baseQuery)
            ->whereDate('date', Carbon::today())
            ->with('service')
            ->get()
            ->sum(function($appt) {
                return $appt->price_paid ?? ($appt->service->price ?? 0);
            });

        // Ganancias del mes (Citas)
        $this->monthlyServices = (clone $baseQuery)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->with('service')
            ->get()
            ->sum(function($appt) {
                return $appt->price_paid ?? ($appt->service->price ?? 0);
            });

        // --- INGRESOS POR POS (VENTAS) ---
        $salesQuery = Sale::where('barbershop_id', $barbershopId);
        if (!$isOwner) {
            $salesQuery->where('user_id', $userId);
        }

        $this->dailySales = (clone $salesQuery)
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        $this->monthlySales = (clone $salesQuery)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // --- TOTALES GENERALES ---
        $this->dailyEarnings = $this->dailyServices + $this->dailySales;
        $this->monthlyEarnings = $this->monthlyServices + $this->monthlySales;
    }

    public function loadAppointments()
    {
        $query = Appointment::where('barbershop_id', auth()->user()->barbershop_id)
            ->with(['user', 'service', 'barber']);
            
        if (!auth()->user()->is_owner) {
            $query->where('barber_id', auth()->id());
        }

        $query = $query->orderBy('date', $this->activeTab === 'upcoming' ? 'asc' : 'desc')
            ->orderBy('start_time', $this->activeTab === 'upcoming' ? 'asc' : 'desc');

        if ($this->activeTab === 'upcoming') {
            $q = $query->where('status', 'scheduled');
            
            if ($this->upcomingFilter === 'today') {
                $q->where('date', '=', now()->format('Y-m-d'));
            } elseif ($this->upcomingFilter === 'month') {
                $q->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year)
                  ->where('date', '>=', now()->format('Y-m-d'));
            } else {
                $q->where('date', '>=', now()->format('Y-m-d'));
            }
            
            $this->appointments = $q->get();
        } else {
            $this->appointments = $query->where('status', 'completed')
                ->whereDate('date', Carbon::today())
                ->get();
        }
    }

    public function completeAppointment($id)
    {
        $appt = Appointment::with(['service', 'user'])
            ->where('id', $id)
            ->where('barbershop_id', auth()->user()->barbershop_id)
            ->first();

        if ($appt && $appt->status === 'scheduled') {
            // Congelar el precio actual del servicio
            $price = $appt->service?->price ?? 0;
            
            $appt->update([
                'status'     => 'completed',
                'price_paid' => $price,
            ]);

            // Notificar al cliente si tiene cuenta registrada
            if ($appt->user) {
                $appt->user->notify(new AppointmentCompleted($appt));
            }

            // Recargar datos respetando la pestaña activa (upcoming) para
            // que el usuario vea la lista actualizada de citas pendientes
            $this->activeTab = 'upcoming';
            $this->refreshData();
            session()->flash('message', '✅ Cita completada. El ingreso fue registrado.');
        }
    }

    public function revertAppointment($id)
    {
        $appt = Appointment::where('id', $id)
            ->where('barbershop_id', auth()->user()->barbershop_id)
            ->where('status', 'completed')
            ->first();

        if ($appt) {
            $appt->update([
                'status' => 'scheduled',
                'price_paid' => null,
            ]);
            $this->refreshData();
            session()->flash('message', 'Cita revertida a Pendiente correctamente.');
        }
    }

    public function cancelAppointment($id)
    {
        $appt = Appointment::with(['user', 'service'])
            ->where('id', $id)
            ->where('barbershop_id', auth()->user()->barbershop_id)
            ->first();

        if ($appt) {
            $appt->update(['status' => 'cancelled']);

            // Notificar al cliente si tiene cuenta registrada
            if ($appt->user) {
                $appt->user->notify(new AppointmentCancelledByBarber($appt));
            }

            $this->refreshData();
            session()->flash('message', 'Cita cancelada. El cliente ha sido notificado.');
        }
    }

    public function markNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'notifications' => Auth::user()->notifications()->latest()->take(5)->get(),
            'unreadCount' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}
