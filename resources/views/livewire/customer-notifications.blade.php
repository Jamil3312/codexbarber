<div class="relative" x-data="{ open: false }">
    <button @click="open = !open; if(open) { $wire.markAsRead() }" class="relative flex items-center justify-center w-12 h-12 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-2xl transition-all shadow-lg">
        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($unread > 0)
            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-60"></span>
                <span class="relative inline-flex rounded-full h-5 w-5 bg-yellow-500 border-2 border-gray-950 text-gray-900 text-[10px] font-black items-center justify-center">{{ $unread > 9 ? '9+' : $unread }}</span>
            </span>
        @endif
    </button>

    {{-- Panel desplegable --}}
    <div x-show="open" @click.outside="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
         class="absolute right-0 mt-3 w-[calc(100vw-2rem)] max-w-sm sm:w-80 bg-gray-900 border border-gray-800 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.8)] z-[100] origin-top-right"
         style="display: none;">
        <div class="p-4 border-b border-gray-800 flex items-center justify-between rounded-t-3xl">
            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Notificaciones</span>
            @if($unread > 0)
                <span class="text-xs text-yellow-500 font-bold">{{ $unread }} nueva(s)</span>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-800/50">
            @forelse($notifications as $notif)
                <div class="p-4 transition-colors hover:bg-gray-800/60 {{ is_null($notif->read_at) ? 'bg-yellow-500/5 border-l-2 border-yellow-500' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 p-1.5 rounded-full {{ is_null($notif->read_at) ? 'bg-yellow-500/20' : 'bg-gray-700' }} shrink-0">
                            @if(str_contains($notif->data['message'] ?? '', 'completada'))
                                <svg class="w-4 h-4 {{ is_null($notif->read_at) ? 'text-yellow-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @else
                                <svg class="w-4 h-4 {{ is_null($notif->read_at) ? 'text-yellow-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-200 leading-snug">{{ $notif->data['message'] ?? 'Notificación' }}</p>
                            <span class="text-[10px] text-gray-500 uppercase font-bold mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <p class="text-sm text-gray-500 font-medium">Sin notificaciones aún</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
