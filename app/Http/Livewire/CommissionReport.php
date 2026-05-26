<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Sale;
use Carbon\Carbon;

class CommissionReport extends Component
{
    public $selectedDate;

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function getReportDataProperty()
    {
        $barbers = User::where('barbershop_id', auth()->user()->barbershop_id)
            ->where('is_barber', true)
            ->get();

        $report = [];

        $date = Carbon::parse($this->selectedDate);
        $month = $date->month;
        $year = $date->year;

        foreach ($barbers as $barber) {
            // --- INGRESOS DEL DÍA SELECCIONADO ---
            $dailyServices = Appointment::where('barber_id', $barber->id)
                ->where('status', 'completed')
                ->whereDate('date', $date->format('Y-m-d'))
                ->get()
                ->sum(function($appt) {
                    return $appt->price_paid ?? ($appt->service->price ?? 0);
                });

            $dailyProducts = Sale::where('user_id', $barber->id)
                ->whereDate('created_at', $date->format('Y-m-d'))
                ->sum('total_amount');

            // --- ACUMULADO DEL MES SELECCIONADO ---
            $monthlyServices = Appointment::where('barber_id', $barber->id)
                ->where('status', 'completed')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get()
                ->sum(function($appt) {
                    return $appt->price_paid ?? ($appt->service->price ?? 0);
                });

            $monthlyProducts = Sale::where('user_id', $barber->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('total_amount');

            $report[] = [
                'barber' => $barber,
                'daily_services' => $dailyServices,
                'daily_products' => $dailyProducts,
                'daily_total' => $dailyServices + $dailyProducts,
                'monthly_services' => $monthlyServices,
                'monthly_products' => $monthlyProducts,
                'monthly_total' => $monthlyServices + $monthlyProducts,
            ];
        }

        return $report;
    }

    public function render()
    {
        // Candado visual y lógico de acceso
        $plan = auth()->user()->barbershop->plan_type ?? 'basic';
        if ($plan !== 'elite') {
            return view('livewire.locked-feature', [
                'featureName' => 'Comisiones y Reportes',
                'requiredPlan' => 'Empire'
            ])->layout('layouts.app');
        }

        return view('livewire.commission-report', [
            'reportData' => $this->reportData,
        ]);
    }
}
