<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamManager extends Component
{
    public $barbers = [];

    public function mount()
    {
        $this->loadBarbers();
    }

    public function loadBarbers()
    {
        $this->barbers = User::where('barbershop_id', auth()->user()->barbershop_id)
            ->where('is_barber', true)
            ->get();
    }

    public function render()
    {
        return view('livewire.team-manager');
    }
}
