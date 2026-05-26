<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CustomerNotifications extends Component
{
    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.customer-notifications', [
            'notifications' => Auth::user()->notifications()->latest()->take(8)->get(),
            'unread' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}
