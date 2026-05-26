<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Models\BlockedDay;
use App\Notifications\AppointmentBooked;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookAppointment extends Component
{
    public $services = [];
    public $barbers = [];
    public $selectedServiceId = null;
    public $selectedBarberId = null;
    public $isAdmin = false;
    public $clientName = '';
    
    public $selectedDate;
    public $selectedTime;
    public $availableSlots = [];
    public $weekDates = [];
    public $isDayBlocked = false;
    public $blockReason = '';

    public $isRecurring = false;
    public $recurringWeeks = 4;

    public function mount()
    {
        Carbon::setLocale('es');
        $this->services = Service::where('barbershop_id', auth()->user()->barbershop_id)->get();
        $this->barbers = User::where('barbershop_id', auth()->user()->barbershop_id)->where('is_barber', true)->get();
        $this->generateWeekDates();
        $this->selectedDate = now()->format('Y-m-d');
        
        // Auto-select first service if exists
        if ($this->services->count() > 0) {
            $this->selectedServiceId = $this->services->first()->id;
        }

        // Auto-select barber if only 1 exists
        if ($this->barbers->count() === 1) {
            $this->selectedBarberId = $this->barbers->first()->id;
        }

        if ($this->selectedServiceId && $this->selectedBarberId) {
            $this->loadSlots();
        }
    }

    public function generateWeekDates()
    {
        $this->weekDates = [];
        $start = now();
        for ($i = 0; $i < 14; $i++) {
            $date = $start->copy()->addDays($i);
            if ($date->isSunday()) continue;
            
            $this->weekDates[] = [
                'date' => $date->format('Y-m-d'),
                'dayName' => substr(ucfirst($date->translatedFormat('D')), 0, 3),
                'dayNum' => $date->format('d'),
                'month' => substr(ucfirst($date->translatedFormat('M')), 0, 3),
            ];
        }
    }

    public function selectService($id)
    {
        $this->selectedServiceId = $id;
        $this->selectedTime = null;
        if ($this->selectedBarberId) {
            $this->loadSlots();
        }
    }

    public function selectBarber($id)
    {
        $this->selectedBarberId = $id;
        $this->selectedTime = null;
        if ($this->selectedServiceId) {
            $this->loadSlots();
        }
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->selectedTime = null;
        if ($this->selectedServiceId && $this->selectedBarberId) {
            $this->loadSlots();
        }
    }

    public function selectTime($time)
    {
        $this->selectedTime = $time;
    }

    public function updatedSelectedDate($value)
    {
        $this->selectedTime = null;
        if ($this->selectedServiceId && $this->selectedBarberId) {
            $this->loadSlots();
        }
    }

    public function loadSlots()
    {
        $this->availableSlots = [];
        $this->isDayBlocked = false;
        $this->blockReason = '';

        $setting = Setting::where('barbershop_id', auth()->user()->barbershop_id)->first();
        if (!$setting || !$this->selectedServiceId || !$this->selectedBarberId) return;

        // Verificar bloqueos del día (puede haber varios: morning + afternoon)
        // OJO: Idealmente BlockedDay también debería tener barber_id en el futuro.
        // Por ahora lo dejamos a nivel de barbería para simplificar MVP, o podrías filtrar por barber_id si existe.
        $blocks = BlockedDay::where('barbershop_id', auth()->user()->barbershop_id)
            ->where('date', $this->selectedDate)
            ->get();

        // Si hay bloqueo completo, bloquear todo
        $fullBlock = $blocks->firstWhere('block_type', 'full');
        if ($fullBlock) {
            $this->isDayBlocked = true;
            $this->blockReason = $fullBlock->reason;
            return;
        }

        $service = Service::find($this->selectedServiceId);
        if (!$service) return;
        
        $duration = $service->duration_minutes;
        $gridInterval = $setting->slot_duration ?? 30; // Grid resolution fallback
        $noticeMinutes = 15;

        // 1. Obtener citas agendadas primero para inyectar sus horarios de finalización
        $bookedAppointments = Appointment::where('date', $this->selectedDate)
            ->where('status', 'scheduled')
            ->where('barber_id', $this->selectedBarberId)
            ->orderBy('start_time')
            ->get();

        // Generar turnos respetando bloqueos parciales
        $morningBlocked   = $blocks->firstWhere('block_type', 'morning');
        $afternoonBlocked = $blocks->firstWhere('block_type', 'afternoon');

        // Generar turno 1 (mañana) solo si no está bloqueado
        if (!$morningBlocked) {
            $this->generateSlotsForShift($setting->start_time_1, $setting->end_time_1, $gridInterval, $duration, $bookedAppointments);
        }

        // Generar turno 2 (tarde) solo si existe y no está bloqueado
        if (!$afternoonBlocked && $setting->start_time_2 && $setting->end_time_2) {
            $this->generateSlotsForShift($setting->start_time_2, $setting->end_time_2, $gridInterval, $duration, $bookedAppointments);
        }

        // Si ambos turnos están bloqueados, mostrar como cerrado
        $hasShift2 = $setting->start_time_2 && $setting->end_time_2;
        if ($morningBlocked && ($afternoonBlocked || !$hasShift2)) {
            $this->isDayBlocked = true;
            $this->blockReason = 'La barbería está cerrada todos los turnos este día.';
            $this->availableSlots = [];
            return;
        }

        // 2. Eliminar duplicados y ordenar los slots cronológicamente
        $this->availableSlots = array_unique($this->availableSlots);
        sort($this->availableSlots);

        $now = now();
        $isToday = $this->selectedDate === $now->format('Y-m-d');
        $minTime = $now->copy()->addMinutes($noticeMinutes);

        // 3. Filtrar colisiones y horarios pasados
        $this->availableSlots = array_values(array_filter($this->availableSlots, function($slot) use ($bookedAppointments, $minTime, $isToday, $duration) {
            $slotStart = Carbon::parse($this->selectedDate . ' ' . $slot);
            $slotEnd = $slotStart->copy()->addMinutes($duration);

            if ($isToday && $slotStart->lessThan($minTime)) return false;

            foreach($bookedAppointments as $booked) {
                $bookedStart = Carbon::parse($booked->date . ' ' . $booked->start_time);
                $bookedEnd = Carbon::parse($booked->date . ' ' . $booked->end_time);

                if ($slotStart->lessThan($bookedEnd) && $bookedStart->lessThan($slotEnd)) {
                    return false;
                }
            }

            return true;
        }));
    }

    private function generateSlotsForShift($start, $end, $gridInterval, $serviceDuration, $bookedAppointments)
    {
        if (!$start || !$end) return;
        
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end)->addMinutes(10); // +10 minutos de tolerancia (salida flexible)

        // A. Cuadrícula Fija
        $current = $startTime->copy();
        while ($current->copy()->addMinutes($serviceDuration)->lessThanOrEqualTo($endTime)) {
            $this->availableSlots[] = $current->format('H:i');
            $current->addMinutes($gridInterval);
        }

        // B. Horarios Dinámicos (Inyección desde end_time)
        foreach ($bookedAppointments as $booked) {
            $bookedEnd = Carbon::parse($booked->end_time);
            
            // Si el servicio nuevo entra perfectamente después de la cita y no excede la tolerancia del turno
            if ($bookedEnd->greaterThanOrEqualTo($startTime) && $bookedEnd->copy()->addMinutes($serviceDuration)->lessThanOrEqualTo($endTime)) {
                $this->availableSlots[] = $bookedEnd->format('H:i');
            }
        }
    }

    public function book()
    {
        $rules = [
            'selectedDate' => 'required|date_format:Y-m-d',
            'selectedTime' => 'required|date_format:H:i',
            'selectedServiceId' => 'required|integer',
            'selectedBarberId' => 'required|integer',
            'isRecurring' => 'boolean',
            'recurringWeeks' => 'required_if:isRecurring,true|integer|min:2|max:12'
        ];
        if ($this->isAdmin) {
            $rules['clientName'] = 'required|string|max:255';
        }
        $this->validate($rules);

        // Verificación de Tenant (Inquilino) - Evita inyección de IDs de otras barberías
        $barbershopId = auth()->user()->barbershop_id;

        $service = Service::where('id', $this->selectedServiceId)
            ->where('barbershop_id', $barbershopId)
            ->firstOrFail();

        $barber = User::where('id', $this->selectedBarberId)
            ->where('barbershop_id', $barbershopId)
            ->where('is_barber', true)
            ->firstOrFail();

        $duration = $service->duration_minutes;
        
        $datesToBook = [];
        $baseDate = Carbon::parse($this->selectedDate);
        $weeksCount = $this->isRecurring ? $this->recurringWeeks : 1;
        
        // Fase 1: Validar todas las fechas requeridas
        for ($i = 0; $i < $weeksCount; $i++) {
            $currentDate = $baseDate->copy()->addWeeks($i)->format('Y-m-d');
            
            $slotStart = Carbon::parse($currentDate . ' ' . $this->selectedTime);
            $slotEnd = $slotStart->copy()->addMinutes($duration);
            
            // Verificar solapamiento
            $overlap = Appointment::where('date', $currentDate)
                ->where('status', 'scheduled')
                ->where('barber_id', $this->selectedBarberId)
                ->get()
                ->contains(function($booked) use ($slotStart, $slotEnd) {
                    $bookedStart = Carbon::parse($booked->date . ' ' . $booked->start_time);
                    $bookedEnd = Carbon::parse($booked->date . ' ' . $booked->end_time);
                    return $slotStart->lessThan($bookedEnd) && $bookedStart->lessThan($slotEnd);
                });
                
            // Verificar bloqueos del día completo
            $blocks = BlockedDay::where('barbershop_id', $barbershopId)
                ->where('date', $currentDate)
                ->get();
            $fullBlock = $blocks->firstWhere('block_type', 'full');
            
            if ($overlap || $fullBlock) {
                $formattedDate = Carbon::parse($currentDate)->translatedFormat('l d \d\e F');
                session()->flash('error', "Conflicto el $formattedDate. Ese horario ya fue reservado o la barbería está cerrada. Por favor selecciona otro o desactiva la repetición.");
                $this->loadSlots();
                return;
            }
            
            $datesToBook[] = [
                'date' => $currentDate,
                'start_time' => $this->selectedTime,
                'end_time' => $slotEnd->format('H:i')
            ];
        }

        // Fase 2: Crear todas las citas
        \Illuminate\Support\Facades\DB::transaction(function () use ($datesToBook, $service) {
            foreach ($datesToBook as $slot) {
                $appointment = Appointment::create([
                    'user_id' => Auth::id(),
                    'service_id' => $service->id,
                    'barber_id' => $this->selectedBarberId,
                    'walkin_name' => $this->isAdmin ? $this->clientName : null,
                    'date' => $slot['date'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'status' => 'scheduled',
                    'barbershop_id' => auth()->user()->barbershop_id
                ]);

                $barber = User::find($this->selectedBarberId);
                if ($barber) {
                    $barber->notify(new AppointmentBooked($appointment));
                }

                Auth::user()->notify(new AppointmentBooked($appointment));
            }
        });

        $msg = $this->isRecurring 
            ? '¡' . $weeksCount . ' citas agendadas exitosamente!'
            : '¡Cita de ' . $service->name . ' agendada exitosamente!';
            
        session()->flash('message', $msg);
        $this->selectedTime = null;
        $this->loadSlots();
        $this->emit('appointment-booked');
        $this->isRecurring = false;
    }

    public function render()
    {
        return view('livewire.book-appointment');
    }
}
