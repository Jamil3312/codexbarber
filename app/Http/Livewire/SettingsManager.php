<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Setting;

class SettingsManager extends Component
{
    public $slot_duration;
    public $start_time_1;
    public $end_time_1;
    public $start_time_2;
    public $end_time_2;
    public $cancellation_notice;
    public $buffer_time;
    
    public $barbers = [];
    public $barberSchedules = [];

    public function mount()
    {
        $setting = Setting::where('barbershop_id', auth()->user()->barbershop_id)->first();
        if ($setting) {
            $this->slot_duration = $setting->slot_duration;
            $this->start_time_1 = \Carbon\Carbon::parse($setting->start_time_1)->format('H:i');
            $this->end_time_1 = \Carbon\Carbon::parse($setting->end_time_1)->format('H:i');
            $this->start_time_2 = $setting->start_time_2 ? \Carbon\Carbon::parse($setting->start_time_2)->format('H:i') : null;
            $this->end_time_2 = $setting->end_time_2 ? \Carbon\Carbon::parse($setting->end_time_2)->format('H:i') : null;
            $this->cancellation_notice = $setting->cancellation_notice;
            $this->buffer_time = $setting->buffer_time ?? 0;
        }

        $this->barbers = \App\Models\User::where('barbershop_id', auth()->user()->barbershop_id)
            ->where('is_barber', true)
            ->get();

        foreach ($this->barbers as $barber) {
            $this->barberSchedules[$barber->id] = [
                'days_off' => $barber->days_off ?? [],
                'day_off_reason' => $barber->day_off_reason ?? '',
            ];
        }
    }

    public function save()
    {
        $this->validate([
            'slot_duration' => 'required|integer|min:10',
            'start_time_1' => 'required|date_format:H:i',
            'end_time_1' => 'required|date_format:H:i|after:start_time_1',
            'start_time_2' => 'nullable|date_format:H:i',
            'end_time_2' => 'nullable|date_format:H:i|after:start_time_2',
            'cancellation_notice' => 'required|integer|min:0',
            'buffer_time' => 'required|integer|min:0|max:60',
        ], [
            'end_time_1.after' => 'El fin del turno 1 debe ser después del inicio.',
            'end_time_2.after' => 'El fin del turno 2 debe ser después de su inicio.'
        ]);

        $setting = Setting::where('barbershop_id', auth()->user()->barbershop_id)->first() ?? new Setting();
        $setting->barbershop_id = auth()->user()->barbershop_id;
        
        $setting->slot_duration = $this->slot_duration;
        $setting->start_time_1 = $this->start_time_1;
        $setting->end_time_1 = $this->end_time_1;
        $setting->start_time_2 = $this->start_time_2;
        $setting->end_time_2 = $this->end_time_2;
        $setting->cancellation_notice = $this->cancellation_notice;
        $setting->buffer_time = $this->buffer_time;
        
        $setting->save();

        foreach ($this->barberSchedules as $barberId => $data) {
            $barber = \App\Models\User::where('id', $barberId)
                ->where('barbershop_id', auth()->user()->barbershop_id)
                ->first();
                
            if ($barber) {
                // Handle livewire checkbox arrays properly
                $daysOff = is_array($data['days_off']) ? array_filter(array_map(function($val) {
                    return is_numeric($val) || is_bool($val) && $val ? (int)$val : null;
                }, $data['days_off']), function($val) { return $val !== null && $val !== false; }) : [];
                
                // If Livewire binds `days_off` as [0 => true, 1 => false], the keys are the actual days.
                // If it binds as an array of values `['0', '1']`, the values are the actual days.
                // To be safe, if we use `<input type="checkbox" wire:model="...days_off" value="0">`, Livewire puts the string values in the array.
                $barber->days_off = array_values(array_map('intval', is_array($data['days_off']) ? $data['days_off'] : []));
                $barber->day_off_reason = $data['day_off_reason'] ?? null;
                $barber->save();
            }
        }

        session()->flash('message', 'Configuración de horarios guardada exitosamente.');
    }

    public function render()
    {
        return view('livewire.settings-manager');
    }
}
