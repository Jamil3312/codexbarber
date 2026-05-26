<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BlockedDay;
use App\Models\Appointment;
use App\Models\Setting;
use App\Notifications\AppointmentCancelledByBlock;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ManageBlockedDays extends Component
{
    public $date;
    public $reason;
    public $blockType = 'full'; // full | morning | afternoon

    protected $rules = [
        'date'      => 'required|date|after_or_equal:today',
        'reason'    => 'nullable|string|max:255',
        'blockType' => 'required|in:full,morning,afternoon',
    ];

    public function blockDate()
    {
        $this->validate();

        $barbershopId = Auth::user()->barbershop_id;

        // Verificar si ya existe bloqueo del mismo tipo (o completo) ese día
        $existing = BlockedDay::where('barbershop_id', $barbershopId)
            ->where('date', $this->date)
            ->where(function ($q) {
                $q->where('block_type', 'full')
                  ->orWhere('block_type', $this->blockType);
            })
            ->first();

        if ($existing) {
            session()->flash('error', 'Ese turno ya está bloqueado para esa fecha.');
            return;
        }

        // Crear el bloqueo
        $blocked = BlockedDay::create([
            'barbershop_id' => $barbershopId,
            'date'          => $this->date,
            'reason'        => $this->reason,
            'block_type'    => $this->blockType,
        ]);

        // Obtener el rango de horas que cubre el bloqueo usando la configuración
        $setting = Setting::where('barbershop_id', $barbershopId)->first();
        [$rangeStart, $rangeEnd] = $this->getBlockRange($setting);

        // Buscar citas programadas que caigan en ese rango
        $affectedAppointments = Appointment::with(['user', 'service'])
            ->where('barbershop_id', $barbershopId)
            ->where('date', $this->date)
            ->where('status', 'scheduled')
            ->get()
            ->filter(function ($appt) use ($rangeStart, $rangeEnd) {
                $start = Carbon::parse($appt->start_time);
                return $start->between($rangeStart, $rangeEnd->copy()->subMinute());
            });

        $cancelledCount = 0;
        foreach ($affectedAppointments as $appt) {
            $appt->update(['status' => 'cancelled']);

            // Notificar al cliente si tiene cuenta
            if ($appt->user) {
                $appt->user->notify(new AppointmentCancelledByBlock($appt, $this->blockType, $this->reason));
            }
            $cancelledCount++;
        }

        $this->reset(['date', 'reason', 'blockType']);

        $suffix = $cancelledCount > 0
            ? " Se cancelaron y notificaron $cancelledCount cita(s) afectada(s)."
            : " No había citas agendadas en ese turno.";

        session()->flash('message', 'Bloqueo registrado.' . $suffix);
    }

    /**
     * Determina el rango de horas del bloqueo según configuración.
     * Morning = turno 1 (start_time_1 → end_time_1)
     * Afternoon = turno 2 (start_time_2 → end_time_2)
     * Full = todo el día (start_time_1 → end_time_2 ?? end_time_1)
     */
    private function getBlockRange($setting): array
    {
        $start1 = $setting ? Carbon::parse($setting->start_time_1) : Carbon::parse('09:00');
        $end1   = $setting ? Carbon::parse($setting->end_time_1)   : Carbon::parse('13:00');
        $start2 = $setting && $setting->start_time_2 ? Carbon::parse($setting->start_time_2) : null;
        $end2   = $setting && $setting->end_time_2   ? Carbon::parse($setting->end_time_2)   : null;

        return match($this->blockType) {
            'morning'   => [$start1, $end1],
            'afternoon' => $start2 ? [$start2, $end2] : [$start1, $end1],
            default     => [$start1, $end2 ?? $end1],
        };
    }

    public function unblockDate($id)
    {
        $blockedDay = BlockedDay::where('id', $id)
            ->where('barbershop_id', Auth::user()->barbershop_id)
            ->first();

        if ($blockedDay) {
            $blockedDay->delete();
            session()->flash('message', 'Bloqueo eliminado. Los clientes ya pueden agendar en ese turno.');
        }
    }

    public function render()
    {
        $blockedDays = BlockedDay::where('barbershop_id', Auth::user()->barbershop_id)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->orderBy('block_type', 'asc')
            ->get();

        return view('livewire.manage-blocked-days', [
            'blockedDays' => $blockedDays
        ]);
    }
}
