<div class="mt-8">
    <h2 class="text-xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600 mb-4 px-2">
        Tus Próximas Citas
    </h2>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-900/50 border border-green-500 rounded-2xl flex items-center gap-3">
            <span class="text-green-300 font-medium text-sm">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-900/50 border border-red-500 rounded-2xl flex items-center gap-3">
            <span class="text-red-300 font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    @forelse($appointments as $appt)
        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-5 mb-4 shadow-lg flex justify-between items-center transition-all hover:bg-gray-750 hover:border-yellow-600/50 relative overflow-hidden">
            <div class="absolute inset-y-0 w-1 left-0 bg-yellow-500 rounded-l-2xl"></div>
            <div class="pl-3">
                <span class="block text-sm font-black text-white mb-1 uppercase tracking-wider">{{ \Carbon\Carbon::parse($appt->date)->translatedFormat('d M, Y') }}</span>
                <span class="inline-flex items-center gap-1.5 bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 px-2 py-0.5 rounded-md text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}
                </span>
            </div>
            
            <button wire:click="cancelAppointment({{ $appt->id }})" onclick="confirm('¿Deseas cancelar esta cita?') || event.stopImmediatePropagation()" class="text-gray-400 hover:text-red-400 transition bg-transparent p-2 rounded-full hover:bg-red-900/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @empty
        <div class="p-8 text-center bg-gray-800/50 rounded-2xl border border-gray-700 border-dashed flex flex-col items-center">
            <svg class="w-10 h-10 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-gray-400 font-medium text-sm">No tienes citas próximas agendadas.</p>
        </div>
    @endforelse
</div>
