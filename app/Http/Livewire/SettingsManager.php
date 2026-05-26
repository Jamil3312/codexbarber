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
        
        $setting->save();

        session()->flash('message', 'Configuración de horarios guardada exitosamente.');
    }

    public function render()
    {
        return view('livewire.settings-manager');
    }
}
