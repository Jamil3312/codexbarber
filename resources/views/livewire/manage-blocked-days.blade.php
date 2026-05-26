<div class="bg-gray-900 border border-gray-800 rounded-[2rem] shadow-xl overflow-hidden p-6">
    <div class="mb-6">
        <h2 class="font-black text-white text-lg">Días Libres / Cierres de Turno</h2>
        <p class="text-gray-400 text-xs mt-1">Bloquea el día completo o un turno específico. Los clientes con citas en ese horario serán notificados y sus citas canceladas automáticamente.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-bold rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-bold rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Formulario de bloqueo --}}
    <form wire:submit.prevent="blockDate" class="space-y-4 mb-8">
        
        {{-- Selector de Tipo de Bloqueo --}}
        <div>
            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-2 ml-1">¿Qué turno deseas cerrar?</label>
            <div class="grid grid-cols-3 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" wire:model="blockType" value="full" class="sr-only peer">
                    <div class="p-3 text-center rounded-2xl border-2 transition-all
                                border-gray-700 bg-gray-800 text-gray-400 text-sm font-bold
                                peer-checked:border-red-500 peer-checked:bg-red-500/10 peer-checked:text-red-400">
                        <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        Día Completo
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" wire:model="blockType" value="morning" class="sr-only peer">
                    <div class="p-3 text-center rounded-2xl border-2 transition-all
                                border-gray-700 bg-gray-800 text-gray-400 text-sm font-bold
                                peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 peer-checked:text-yellow-400">
                        <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Mañana
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" wire:model="blockType" value="afternoon" class="sr-only peer">
                    <div class="p-3 text-center rounded-2xl border-2 transition-all
                                border-gray-700 bg-gray-800 text-gray-400 text-sm font-bold
                                peer-checked:border-blue-400 peer-checked:bg-blue-500/10 peer-checked:text-blue-400">
                        <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        Tarde
                    </div>
                </label>
            </div>
            @error('blockType') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1 ml-1">Fecha</label>
                <input type="date" wire:model="date" required min="{{ date('Y-m-d') }}"
                       class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-yellow-500 focus:border-yellow-500 cursor-pointer">
                @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="flex-[2]">
                <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1 ml-1">Motivo (Opcional)</label>
                <input type="text" wire:model="reason" placeholder="Ej. Capacitación, emergencia, etc..."
                       class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-yellow-500 focus:border-yellow-500">
                @error('reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Aviso de impacto --}}
        <div class="flex items-start gap-2 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl">
            <svg class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <p class="text-xs text-amber-300/80">Las citas que ya estaban agendadas en ese turno serán <strong>canceladas automáticamente</strong> y los clientes recibirán una notificación explicando el cierre.</p>
        </div>

        <button type="submit" wire:loading.attr="disabled"
                class="w-full bg-gray-800 hover:bg-gray-700 disabled:opacity-50 text-yellow-500 border border-gray-700 hover:border-yellow-500/50 font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
            <span wire:loading.remove>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </span>
            <span wire:loading>
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </span>
            <span wire:loading.remove>Registrar Cierre y Notificar Clientes</span>
            <span wire:loading>Procesando...</span>
        </button>
    </form>

    {{-- Tabla de bloqueos activos --}}
    <div class="border border-gray-800 rounded-2xl overflow-hidden bg-gray-900">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="text-[10px] tracking-widest text-gray-600 uppercase font-black bg-gray-800/50">
                    <th class="p-3 border-b border-gray-800">Fecha</th>
                    <th class="p-3 border-b border-gray-800">Turno</th>
                    <th class="p-3 border-b border-gray-800">Motivo</th>
                    <th class="p-3 border-b border-gray-800 text-right">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blockedDays as $day)
                    <tr class="hover:bg-gray-800/40 transition-colors">
                        <td class="p-3 border-b border-gray-800/50">
                            <span class="text-white font-bold text-sm bg-gray-800 px-3 py-1 rounded-lg whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($day->date)->translatedFormat('d M, Y') }}
                            </span>
                        </td>
                        <td class="p-3 border-b border-gray-800/50">
                            @if($day->block_type === 'full')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-500/10 text-red-400 border border-red-500/20 rounded-lg text-[10px] font-black uppercase">
                                    🚫 Día Completo
                                </span>
                            @elseif($day->block_type === 'morning')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 rounded-lg text-[10px] font-black uppercase">
                                    ☀️ Mañana
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-lg text-[10px] font-black uppercase">
                                    🌙 Tarde
                                </span>
                            @endif
                        </td>
                        <td class="p-3 border-b border-gray-800/50">
                            <span class="text-gray-400 text-sm">{{ $day->reason ?: '—' }}</span>
                        </td>
                        <td class="p-3 border-b border-gray-800/50 text-right">
                            <button wire:click="unblockDate({{ $day->id }})"
                                    wire:loading.attr="disabled"
                                    class="text-red-500/70 hover:text-red-400 hover:bg-red-500/10 p-2 rounded-lg transition-all"
                                    title="Eliminar Bloqueo"
                                    wire:confirm="¿Eliminar este bloqueo? Los clientes podrán volver a agendar en ese turno.">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm">No hay bloqueos activos.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
