<div class="max-w-4xl mx-auto bg-gray-900 border border-gray-800 rounded-[2.5rem] shadow-2xl p-4 sm:p-6 text-white relative"
     wire:poll.15s="refreshData"
     x-data="{ walkinOpen: false }">
    <!-- Decoración Base (Aislada por overflow) -->
    <div class="absolute inset-0 rounded-[2.5rem] overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 left-0 h-32 opacity-10 transform skew-y-2 -translate-y-8" style="background-image: linear-gradient(to left, rgb(var(--primary-dark)), rgb(var(--primary-main)), rgb(var(--primary-dark)))"></div>
    </div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight text-transparent bg-clip-text" style="background-image: linear-gradient(to right, rgb(var(--primary-light)), var(--primary-color), rgb(var(--primary-dark)))">
                    Panel Administrativo
                </h1>
                <div class="flex items-center gap-2 mt-1">
                    <p class="text-gray-400 text-sm italic">{{ auth()->user()->barbershop->name ?? 'Sin Barbería' }}</p>
                    @php
                        $planColors = [
                            'basic' => 'bg-gray-800 text-gray-400 border-gray-700',
                            'pro' => 'bg-blue-900/30 text-blue-400 border-blue-500/30',
                            'elite' => 'bg-purple-900/30 text-purple-400 border-purple-500/30'
                        ];
                        $planNames = ['basic' => 'Starter', 'pro' => 'Studio', 'elite' => 'Elite'];
                        $currentPlan = auth()->user()->barbershop->plan_type ?? 'basic';
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase border {{ $planColors[$currentPlan] }}">
                        {{ $planNames[$currentPlan] }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Botón Nueva Cita --}}
                <button @click="walkinOpen = true"
                        class="bg-primary-dynamic text-gray-900 font-black px-4 py-2.5 rounded-2xl transition-all flex items-center gap-2 hover:-translate-y-0.5 active:scale-95 text-sm" style="box-shadow: 0 8px 20px var(--primary-glow); filter: brightness(1.1);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Nueva Cita
                </button>

                {{-- Campana de Notificaciones --}}
                <div class="relative group">
                    <button class="flex items-center justify-center bg-gray-800/80 hover:bg-gray-700 p-3 rounded-2xl border border-gray-700 transition-all relative">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-primary-dynamic"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 border-2 border-gray-900 text-gray-900 text-[9px] font-black items-center justify-center bg-primary-dynamic">{{ $unreadCount }}</span>
                            </span>
                        @endif
                    </button>
                    <div class="absolute right-0 mt-3 w-[calc(100vw-2rem)] max-w-sm sm:w-80 bg-gray-900 border border-gray-800 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.8)] invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all z-[100] origin-top-right">
                        <div class="p-3 bg-gray-800/50 border-b border-gray-800 flex justify-between items-center rounded-t-3xl">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Actividad Reciente</span>
                            @if($unreadCount > 0)
                                <button wire:click="markNotificationsAsRead" class="text-xs text-yellow-500 hover:text-yellow-400 font-bold">Limpiar</button>
                            @endif
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($notifications as $notification)
                                <div class="p-3 border-b border-gray-800/50 hover:bg-gray-800/60 transition-colors {{ is_null($notification->read_at) ? 'bg-yellow-500/5' : 'opacity-60' }}">
                                    <p class="text-sm text-gray-200 leading-snug">{{ $notification->data['message'] }}</p>
                                    <span class="text-[10px] text-gray-500 uppercase font-bold mt-0.5 block">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500 text-sm">Sin alertas nuevas</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Navigation Tabs --}}
        <div class="flex gap-2 mt-6 bg-gray-800/50 p-1.5 rounded-2xl border border-gray-800">
            <button wire:click="setActiveTab('upcoming')"
                    class="flex-1 py-2.5 px-4 rounded-xl text-sm font-black transition-all flex items-center justify-center gap-2
                    {{ $activeTab === 'upcoming' ? 'bg-primary-dynamic text-gray-900 shadow-lg shadow-primary-dynamic' : 'text-gray-200 hover:text-white hover:bg-gray-800/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Gestión de Citas
            </button>
            <button wire:click="setActiveTab('completed')"
                    class="flex-1 py-2.5 px-4 rounded-xl text-sm font-black transition-all flex items-center justify-center gap-2
                    {{ $activeTab === 'completed' ? 'bg-primary-dynamic text-gray-900 shadow-lg shadow-primary-dynamic' : 'text-gray-200 hover:text-white hover:bg-gray-800/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Control Financiero
            </button>
            <button wire:click="setActiveTab('agenda')"
                    class="flex-1 py-2.5 px-4 rounded-xl text-sm font-black transition-all flex items-center justify-center gap-2
                    {{ $activeTab === 'agenda' ? 'bg-primary-dynamic text-gray-900 shadow-lg shadow-primary-dynamic' : 'text-gray-200 hover:text-white hover:bg-gray-800/60' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="hidden sm:inline">Config. </span>Agenda
            </button>
        </div>{{-- /tabs nav --}}

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-900/40 border border-green-500/40 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-green-300 font-bold text-sm">{{ session('message') }}</span>
        </div>
    @endif

    {{-- ===== PESTAÑA GESTIÓN DE CITAS ===== --}}
    @if($activeTab === 'upcoming')
    <div class="animate-fade-in">
        <div class="bg-gray-900 border border-gray-800 rounded-[2rem] shadow-xl overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4 p-5 border-b border-gray-800">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">Próximas Citas</h2>
                    <p class="text-sm text-gray-400">Citas pendientes de atender</p>
                </div>
                <div class="flex items-center gap-3">
                    <select wire:model="upcomingFilter" class="bg-gray-900 border border-gray-700 text-sm font-semibold text-gray-300 rounded-xl px-4 py-2 focus:border-primary-dynamic focus:ring-primary-dynamic transition-colors cursor-pointer outline-none">
                        <option value="today">Solo Hoy</option>
                        <option value="month">Este Mes</option>
                        <option value="all">Todas (Futuras)</option>
                    </select>
                    <div class="bg-gray-800/50 border border-gray-700 px-4 py-2 rounded-xl">
                        <span class="text-sm font-bold text-gray-300">{{ $appointments->count() }} cita(s)</span>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-1 p-3">
                    <thead>
                        <tr class="text-[10px] tracking-widest text-gray-600 uppercase font-black">
                            <th class="pb-1 px-4">Horario</th>
                            <th class="pb-1 px-4">Cliente / Servicio</th>
                            <th class="pb-1 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appt)
                            <tr class="bg-gray-800/40 hover:bg-gray-800 transition-all rounded-2xl group">
                                <td class="p-4 rounded-l-2xl">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-500 font-bold uppercase">{{ \Carbon\Carbon::parse($appt->date)->translatedFormat('d M') }}</span>
                                        <span class="bg-gray-900 text-white px-2.5 py-1 rounded-lg text-sm font-black border border-gray-700 group-hover:border-yellow-500/30 transition-colors mt-0.5 inline-block w-fit">
                                            {{ \Carbon\Carbon::parse($appt->start_time)->format('H:i') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="text-white font-black text-sm">
                                            @if($appt->walkin_name)
                                                {{ $appt->walkin_name }}
                                                <span class="text-[9px] bg-yellow-500/10 text-yellow-500 px-1.5 py-0.5 rounded ml-1 align-middle">WALK-IN</span>
                                            @else
                                                {{ $appt->user->name ?? 'Cliente' }}
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500 font-semibold mt-0.5">
                                            {{ $appt->service->name ?? 'Servicio' }} · <span class="text-yellow-600">Q{{ number_format($appt->service->price ?? 0, 2) }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4 text-right rounded-r-2xl">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            wire:click="completeAppointment({{ $appt->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="completeAppointment({{ $appt->id }})"
                                            class="bg-green-500/10 hover:bg-green-500 text-green-400 hover:text-gray-900 p-2 rounded-xl transition-all border border-green-500/20 disabled:opacity-40"
                                            title="Marcar como Completada">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                        <button
                                            onclick="return confirm('¿Cancelar esta cita?')"
                                            wire:click="cancelAppointment({{ $appt->id }})"
                                            wire:loading.attr="disabled"
                                            class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white p-2 rounded-xl transition-all border border-red-500/20 disabled:opacity-40"
                                            title="Cancelar Cita">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-16 text-center text-gray-600">
                                    <svg class="w-14 h-14 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="font-bold">No hay citas pendientes</p>
                                    <p class="text-sm mt-1">Todo al día por aquí 🎉</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== PESTAÑA CONTROL FINANCIERO ===== --}}
    @if($activeTab === 'completed')
    <div class="animate-fade-in">

        {{-- Tarjetas de Ganancias --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
            <div class="bg-gray-900 border border-gray-800 hover:border-primary-dynamic p-6 rounded-[2rem] relative overflow-hidden shadow-xl transition-all group flex flex-col justify-between">
                <div class="absolute top-0 right-0 p-5 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none">
                    <svg class="w-16 h-16 text-primary-dynamic" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-2">Cierre del Día (Total General)</p>
                    <span class="text-4xl font-black text-white leading-none">Q{{ number_format($dailyEarnings, 2) }}</span>
                </div>
                <div class="mt-4 flex gap-4 text-xs font-semibold">
                    <div class="flex flex-col border-r border-gray-700 pr-4">
                        <span class="text-gray-500">Cortes:</span>
                        <span class="text-purple-400">Q{{ number_format($dailyServices, 2) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500">POS (Productos):</span>
                        <span class="text-yellow-400">Q{{ number_format($dailySales, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 hover:border-primary-dynamic p-6 rounded-[2rem] relative overflow-hidden shadow-xl transition-all group flex flex-col justify-between">
                <div class="absolute top-0 right-0 p-5 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none">
                    <svg class="w-16 h-16 text-primary-dynamic" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-2">Acumulado del Mes</p>
                    <span class="text-4xl font-black text-primary-dynamic leading-none">Q{{ number_format($monthlyEarnings, 2) }}</span>
                </div>
                <div class="mt-4 flex gap-4 text-xs font-semibold">
                    <div class="flex flex-col border-r border-gray-700 pr-4">
                        <span class="text-gray-500">Cortes:</span>
                        <span class="text-purple-400">Q{{ number_format($monthlyServices, 2) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500">POS (Productos):</span>
                        <span class="text-yellow-400">Q{{ number_format($monthlySales, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Finalizadas --}}
        <div class="bg-gray-900 border border-gray-800 rounded-[2rem] shadow-xl overflow-hidden">
            <div class="p-5 border-b border-gray-800 flex items-center justify-between">
                <div>
                    <h2 class="font-black text-white text-lg">Historial del Día</h2>
                    <p class="text-gray-500 text-xs mt-0.5">Servicios completados hoy</p>
                </div>
                <span class="bg-green-500/10 text-green-500 border border-green-500/20 text-xs font-black px-3 py-1 rounded-full">
                    Q{{ number_format($dailyEarnings, 2) }} cobrado
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-1 p-3">
                    <thead>
                        <tr class="text-[10px] tracking-widest text-gray-600 uppercase font-black">
                            <th class="pb-1 px-4">Hora</th>
                            <th class="pb-1 px-4">Cliente / Servicio</th>
                            <th class="pb-1 px-4 text-right">Cobrado / Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appt)
                            <tr class="bg-gray-800/40 hover:bg-gray-800 transition-all group">
                                <td class="p-4 rounded-l-2xl">
                                    <span class="text-sm font-black text-white">{{ \Carbon\Carbon::parse($appt->start_time)->format('H:i') }}</span>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="text-white font-black text-sm">
                                            @if($appt->walkin_name)
                                                {{ $appt->walkin_name }} <span class="text-[9px] bg-yellow-500/10 text-yellow-500 px-1.5 py-0.5 rounded ml-1">WALK-IN</span>
                                            @else
                                                {{ $appt->user->name ?? 'Cliente' }}
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500 font-semibold mt-0.5">{{ $appt->service->name ?? 'Servicio' }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-right rounded-r-2xl">
                                    <div class="flex justify-end items-center gap-2">
                                        <span class="text-green-400 font-black text-sm">Q{{ number_format($appt->price_paid ?? $appt->service->price ?? 0, 2) }}</span>
                                        <button
                                            wire:click="revertAppointment({{ $appt->id }})"
                                            wire:loading.attr="disabled"
                                            class="bg-primary-dynamic bg-opacity-10 hover:bg-primary-dynamic text-primary-dynamic hover:text-gray-900 p-1.5 rounded-xl transition-all border border-primary-dynamic disabled:opacity-40"
                                            title="Revertir a Pendiente">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-14 text-center text-gray-600">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="font-bold">Sin servicios completados hoy</p>
                                    <p class="text-sm mt-1">Las citas cobradas aparecerán aquí</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== PESTAÑA CONFIG. AGENDA ===== --}}
    @if($activeTab === 'agenda')
    <div class="animate-fade-in">
        @livewire('manage-blocked-days')
    </div>
    @endif

    {{-- Modal Nueva Cita Presencial --}}
    <div x-show="walkinOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="walkinOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-950/90 backdrop-blur-xl" @click="walkinOpen = false"></div>
            
            <div x-show="walkinOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-gray-900 rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.8)] ring-1 ring-gray-800 w-full max-w-md border-t-8 border-yellow-500 z-10">
                <button @click="walkinOpen = false"
                        class="absolute top-5 right-5 text-gray-500 hover:text-white bg-gray-800/80 rounded-full p-2 backdrop-blur transition-transform hover:rotate-90">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="p-4">
                    <div wire:ignore>
                        @livewire('book-appointment', ['isAdmin' => true])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
