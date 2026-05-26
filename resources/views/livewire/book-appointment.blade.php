<div class="max-w-md mx-auto bg-gray-900 text-white min-h-screen pb-12 rounded-t-3xl shadow-2xl font-sans relative overflow-hidden" 
     style="box-shadow: 0 -10px 40px rgba(0,0,0,0.5);">
    
    <!-- Header Decorativo Base -->
    <div class="absolute top-0 left-0 right-0 h-64 opacity-20 transform -skew-y-6 -translate-y-20" style="background-image: linear-gradient(to bottom right, rgb(var(--primary-dark)), rgb(var(--primary-main)), rgb(var(--primary-dark)))"></div>

    <div class="p-6 relative z-10">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-transparent bg-clip-text" style="background-image: linear-gradient(to right, rgb(var(--primary-light)), var(--primary-color))">
                Reservar Cita
            </h1>
            <div class="p-2 bg-gray-800 rounded-full border border-gray-700 shadow-inner">
                <svg class="w-6 h-6 text-primary-dynamic" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-500 rounded-2xl flex items-center gap-3 animate-fade-in-down" role="alert">
                <div class="bg-green-500 rounded-full p-1 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-green-300 font-medium text-sm">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-900/50 border border-red-500 rounded-2xl flex items-center gap-3 animate-fade-in-down" role="alert">
                <div class="bg-red-500 rounded-full p-1 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <span class="text-red-300 font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Servicios -->
        @if(count($services) > 0)
            <h2 class="text-lg font-semibold text-gray-300 mb-4 px-1">¿Qué servicio deseas?</h2>
            <div class="flex overflow-x-auto pb-4 gap-3 snap-x hide-scrollbar mb-4">
                @foreach($services as $svc)
                    <button 
                        wire:click="selectService({{ $svc->id }})" 
                        class="snap-center flex-shrink-0 w-36 flex flex-col items-start justify-between p-4 rounded-3xl transition-all duration-300 shadow-md border text-left
                        {{ $selectedServiceId == $svc->id 
                            ? 'text-gray-900 scale-105 shadow-primary-dynamic border-transparent' 
                            : 'bg-gray-800 text-gray-300 border-gray-700 hover:bg-gray-750' }}"
                        style="{{ $selectedServiceId == $svc->id ? 'background-image: linear-gradient(to bottom right, var(--primary-color), rgb(var(--primary-dark))); border-color: rgb(var(--primary-light)); box-shadow: 0 5px 15px var(--primary-glow);' : '' }}"
                    >
                        <span class="font-bold mb-1 leading-tight">{{ $svc->name }}</span>
                        <div class="flex flex-col mt-2">
                            <span class="font-black text-lg">Q{{ number_format($svc->price, 2) }}</span>
                            <span class="text-xs font-semibold opacity-80 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $svc->duration_minutes }} min
                            </span>
                        </div>
                    </button>
                @endforeach
            </div>
            
            <div class="h-px bg-gray-800 w-full my-6"></div>
        @else
            <div class="p-6 text-center bg-gray-800 border border-gray-700 rounded-2xl mb-6">
                <p class="text-gray-400">Nuestros servicios se están actualizando...</p>
            </div>
        @endif

        <!-- Barberos -->
        @if(count($barbers) > 1 && $selectedServiceId)
            <div class="animate-fade-in-down mb-6">
                <h2 class="text-lg font-semibold text-gray-300 mb-4 px-1">¿Con quién te atiendes?</h2>
                <div class="flex overflow-x-auto pb-4 gap-3 snap-x hide-scrollbar">

                    @foreach($barbers as $barber)
                        <button 
                            wire:click="selectBarber({{ $barber->id }})" 
                            class="snap-center flex-shrink-0 w-24 flex flex-col items-center justify-center p-3 rounded-2xl transition-all duration-300 shadow-md border 
                            {{ $selectedBarberId == $barber->id 
                                ? 'text-gray-900 scale-105 shadow-primary-dynamic border-transparent' 
                                : 'bg-gray-800 text-gray-300 border-gray-700 hover:bg-gray-750' }}"
                            style="{{ $selectedBarberId == $barber->id ? 'background-image: linear-gradient(to bottom right, var(--primary-color), rgb(var(--primary-dark))); border-color: rgb(var(--primary-light)); box-shadow: 0 5px 15px var(--primary-glow);' : '' }}"
                        >
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-700 to-gray-600 mb-2 flex items-center justify-center text-white font-black text-lg shadow-inner border border-gray-500">
                                {{ substr($barber->name, 0, 1) }}
                            </div>
                            <span class="font-bold text-xs leading-tight text-center line-clamp-1">{{ explode(' ', trim($barber->name))[0] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="h-px bg-gray-800 w-full my-6"></div>
        @endif

        <!-- Fechas -->
        <div class="transition-all duration-500 {{ ($selectedServiceId && ($selectedBarberId || count($barbers) <= 1)) ? 'opacity-100 translate-y-0' : 'opacity-50 pointer-events-none grayscale' }}">
            <div class="flex items-center justify-between mb-4 px-1">
                <h2 class="text-lg font-semibold text-gray-300">Selecciona un día</h2>
                <div>
                    <input type="date" wire:model="selectedDate" min="{{ date('Y-m-d') }}" class="bg-gray-800 border border-gray-700 text-gray-200 text-xs font-bold uppercase px-3 py-2 rounded-xl focus:ring-primary-dynamic focus:border-primary-dynamic transition-colors cursor-pointer shadow-sm" title="Abrir calendario">
                </div>
            </div>
        <div class="flex overflow-x-auto pb-4 gap-3 snap-x hide-scrollbar">
            @foreach($weekDates as $day)
                <button 
                    wire:click="selectDate('{{ $day['date'] }}')" 
                    class="snap-center flex-shrink-0 w-20 flex flex-col items-center justify-center py-4 rounded-3xl transition-all duration-300 shadow-md border 
                    {{ $selectedDate === $day['date'] 
                        ? 'text-gray-900 scale-105 border-transparent shadow-primary-dynamic' 
                        : 'bg-gray-800 text-gray-400 border-gray-700 hover:bg-gray-700' }}"
                    style="{{ $selectedDate === $day['date'] ? 'background-image: linear-gradient(to bottom, rgb(var(--primary-light)), rgb(var(--primary-dark))); border-color: rgb(var(--primary-light)); box-shadow: 0 5px 15px var(--primary-glow);' : '' }}"
                >
                    <span class="text-xs uppercase font-bold tracking-wider mb-1 opacity-80">{{ $day['month'] }}</span>
                    <span class="text-2xl font-black mb-1 leading-none">{{ $day['dayNum'] }}</span>
                    <span class="text-xs opacity-90">{{ $day['dayName'] }}</span>
                </button>
            @endforeach
        </div>

        </div>

        <!-- Horas -->
        <div class="mt-8 transition-all duration-500 {{ ($selectedDate && $selectedServiceId && $selectedBarberId) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4 pointer-events-none absolute' }}">
            <h2 class="text-lg font-semibold text-gray-300 mb-4 px-1">Horas Disponibles</h2>
            
            @if($isDayBlocked)
                <div class="p-8 text-center bg-red-900/20 rounded-3xl border border-red-500/30 flex flex-col items-center">
                    <div class="bg-red-500/20 p-3 rounded-full mb-3">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-red-400 font-bold text-lg">Cerrado este día</h3>
                    <p class="text-red-300/70 text-sm mt-1">{{ $blockReason ?: 'La barbería no recibirá citas en esta fecha.' }}</p>
                </div>
            @elseif(count($availableSlots) > 0)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($availableSlots as $time)
                        <button 
                            wire:click="selectTime('{{ $time }}')"
                            class="py-3 px-2 rounded-xl text-center transition-all duration-200 border text-sm font-semibold tracking-wide flex justify-center items-center gap-2
                            {{ $selectedTime === $time 
                                ? 'bg-primary-dynamic text-gray-900 shadow-primary-dynamic' 
                                : 'bg-gray-800/80 text-gray-300 border-gray-700 hover:border-gray-500 hover:bg-gray-700' }}"
                            style="{{ $selectedTime === $time ? 'border-color: rgb(var(--primary-light)); box-shadow: 0 0 15px var(--primary-glow);' : '' }}"
                        >
                            @if($selectedTime === $time)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                            {{ $time }}
                        </button>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-gray-800/50 rounded-3xl border border-gray-700 flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-400 font-medium">No hay horas disponibles para este día.</p>
                </div>
            @endif
        </div>

        <!-- Botón Confirmar -->
        @if($selectedTime)
            <div class="mt-10 animate-fade-in-up transition-all">
                
                <div class="mb-6 p-4 bg-gray-800/60 border border-gray-700 rounded-2xl">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative flex items-center">
                            <input type="checkbox" wire:model="isRecurring" class="peer sr-only">
                            <div class="w-10 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-dynamic"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-200">Repetir esta cita semanalmente</span>
                    </label>

                    @if($isRecurring)
                    <div class="mt-4 animate-fade-in-down">
                        <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-wide">¿Por cuántas semanas?</label>
                        <select wire:model="recurringWeeks" class="w-full bg-gray-900 border border-gray-700 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3 appearance-none">
                            <option value="2">2 semanas ({{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l') }}s)</option>
                            <option value="4">4 semanas (1 mes)</option>
                            <option value="8">8 semanas (2 meses)</option>
                            <option value="12">12 semanas (3 meses)</option>
                        </select>
                    </div>
                    @endif
                </div>

                @if($isAdmin)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-300 mb-1">Nombre del Cliente Presencial</label>
                        <input type="text" wire:model="clientName" placeholder="Ej. Juan Pérez" class="w-full bg-gray-800 border {{ $errors->has('clientName') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3">
                        @error('clientName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endif
                <button 
                    wire:click="book"
                    class="w-full relative group overflow-hidden bg-primary-dynamic text-gray-900 font-bold text-lg py-5 rounded-2xl transition-all duration-300 flex items-center justify-center gap-3 shadow-primary-dynamic"
                    style="box-shadow: 0 10px 25px var(--primary-glow); filter: brightness(1.05);"
                >
                    <span class="relative z-10">
                        {{ $isRecurring ? 'Confirmar ' . $recurringWeeks . ' Citas a las ' . $selectedTime : 'Confirmar Cita a las ' . $selectedTime }}
                    </span>
                    <svg class="w-5 h-5 relative z-10 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                </button>
            </div>
        @endif
    </div>
    
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.4s ease-out; }
        .animate-fade-in-up { animation: fade-in-up 0.5s ease-out forwards; }
    </style>
</div>
