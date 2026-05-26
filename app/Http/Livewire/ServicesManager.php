<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ServicesManager extends Component
{
    public $services;
    public $name, $price, $duration_minutes, $service_id;
    public $isModalOpen = false;

    public function mount()
    {
        if (!Auth::user()->is_barber) {
            abort(403, 'Acceso Denegado');
        }
    }

    public function render()
    {
        $this->services = Service::where('barbershop_id', auth()->user()->barbershop_id)->get();
        return view('livewire.services-manager');
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm()
    {
        $this->name = '';
        $this->price = '';
        $this->duration_minutes = '';
        $this->service_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        if ($this->service_id) {
            $service = Service::where('id', $this->service_id)
                ->where('barbershop_id', auth()->user()->barbershop_id)
                ->firstOrFail();
            
            $service->update([
                'name' => $this->name,
                'price' => $this->price,
                'duration_minutes' => $this->duration_minutes,
            ]);
        } else {
            Service::create([
                'name' => $this->name,
                'price' => $this->price,
                'duration_minutes' => $this->duration_minutes,
                'barbershop_id' => auth()->user()->barbershop_id
            ]);
        }

        session()->flash('message', $this->service_id ? 'Servicio editado con éxito.' : 'Nuevo servicio agregado.');

        $this->closeModal();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $service = Service::where('id', $id)->where('barbershop_id', auth()->user()->barbershop_id)->firstOrFail();
        $this->service_id = $id;
        $this->name = $service->name;
        $this->price = $service->price;
        $this->duration_minutes = $service->duration_minutes;

        $this->openModal();
    }

    public function delete($id)
    {
        Service::where('id', $id)->where('barbershop_id', auth()->user()->barbershop_id)->delete();
        session()->flash('message', 'Servicio eliminado.');
    }
}
