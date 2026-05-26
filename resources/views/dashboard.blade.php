<x-app-layout>
    <div class="py-6 sm:py-12 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header del Cliente --}}
            <div class="relative z-50 flex justify-between items-center mb-8 px-4 sm:px-0">
                <div>
                    <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">Mi Panel</h2>
                    <p class="text-gray-500 text-sm mt-0.5">Hola, <span class="text-white font-bold">{{ Auth::user()->name }}</span> 👋</p>
                </div>

                {{-- Campana de Notificaciones (Livewire) --}}
                @livewire('customer-notifications')
            </div>

            <div class="flex flex-col lg:flex-row justify-center gap-8 px-4 sm:px-0">
                
                <!-- Widget Principal Reservas -->
                <div class="w-full lg:w-[28rem] rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] ring-4 sm:ring-8 ring-gray-900 overflow-hidden relative">
                    <div class="absolute inset-0 bg-yellow-500/5 backdrop-blur-2xl z-0 pointer-events-none rounded-[2.5rem]"></div>
                    <div class="relative z-10 bg-gray-900 border border-gray-800">
                        @livewire('book-appointment')
                    </div>
                </div>

                <!-- Resumen Cliente -->
                <div class="w-full lg:w-96 flex flex-col pt-4">
                    <div class="bg-gray-900 border border-gray-800 rounded-3xl p-6 shadow-xl relative overflow-hidden">
                        <div class="relative z-10 flex items-center gap-4 mb-2">
                            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center text-gray-900 justify-center font-bold text-xl shadow-lg border-2 border-gray-900 ring-2 ring-yellow-500/50">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-lg leading-tight">{{ Auth::user()->name }}</h3>
                                <p class="text-sm text-yellow-500 font-medium flex items-center gap-1 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    Cliente Frecuente
                                </p>
                            </div>
                        </div>
                        
                        @livewire('customer-appointments')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

