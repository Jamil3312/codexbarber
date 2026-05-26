<div class="max-w-xl mx-auto bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
    <!-- Header Decorativo Base -->
    <div class="absolute top-0 right-0 left-0 h-48 bg-gradient-to-br from-yellow-700 via-yellow-600 to-yellow-800 opacity-10 transform -skew-y-3 -translate-y-16"></div>

    <div class="relative z-10">
        <h2 class="text-3xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600 mb-6 flex items-center gap-3">
            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            Configuración del Barbero
        </h2>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-500 rounded-2xl flex items-center gap-3">
                <div class="bg-green-500 rounded-full p-1 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-green-300 font-medium">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-6">
            
            <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700">
                <label class="block text-sm font-semibold text-gray-300 mb-2">Intervalo de Agenda (minutos)</label>
                <input type="number" wire:model="slot_duration" class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3 shadow-inner">
                <p class="text-xs text-gray-500 mt-2">Cada cuántos minutos se mostrarán los botones disponibles (Ej: 15 o 30). El sistema ocultará los botones inteligentemente según la duración del servicio seleccionado.</p>
                @error('slot_duration') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700">
                <h3 class="font-bold text-gray-300 mb-4 border-b border-gray-700 pb-2">Turno Principal (Mañana)</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Hora Inicio</label>
                        <input type="time" wire:model="start_time_1" class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3 shadow-inner">
                        @error('start_time_1') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Hora Fin</label>
                        <input type="time" wire:model="end_time_1" class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3 shadow-inner">
                        @error('end_time_1') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700">
                <h3 class="font-bold text-gray-300 mb-4 border-b border-gray-700 pb-2">Segundo Turno (Tarde - Opcional)</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Hora Inicio</label>
                        <input type="time" wire:model="start_time_2" class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3 shadow-inner">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Hora Fin</label>
                        <input type="time" wire:model="end_time_2" class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3 shadow-inner">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3 flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Deja en blanco si solo trabajas jornada corrida.</p>
            </div>

            <div class="bg-gray-800 p-5 rounded-2xl border border-gray-700">
                <label class="block text-sm font-semibold text-gray-300 mb-2">Notice de Cancelación (Horas antes)</label>
                <input type="number" wire:model="cancellation_notice" class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3 shadow-inner">
                <p class="text-xs text-gray-500 mt-2">Diferencia de tiempo mínima permitida para agendar o cancelar hoy.</p>
                @error('cancellation_notice') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full relative group overflow-hidden bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold text-lg py-4 rounded-2xl shadow-[0_5px_15px_rgba(234,179,8,0.3)] transition-all duration-300">
                <span class="relative z-10 flex justify-center items-center gap-2">
                    Guardar Configuración
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                </span>
                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
            </button>
        </form>
    </div>
</div>
