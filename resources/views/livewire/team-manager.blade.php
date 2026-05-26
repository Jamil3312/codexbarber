<div class="max-w-4xl mx-auto bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
    <!-- Header Decorativo Base -->
    <div class="absolute top-0 right-0 left-0 h-48 bg-gradient-to-br from-purple-700 via-purple-600 to-indigo-800 opacity-10 transform -skew-y-3 -translate-y-16"></div>

    <div class="relative z-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-indigo-500 flex items-center gap-3">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Equipo de Barberos
            </h2>
            <!-- Upsell / Soporte (Reemplaza el botón de crear) -->
            <a href="#" class="bg-gray-800 hover:bg-gray-700 text-purple-400 font-bold py-2.5 px-5 rounded-xl border border-gray-700 transition-all flex items-center gap-2 text-sm shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Contactar Soporte
            </a>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-500 rounded-2xl flex items-center gap-3">
                <div class="bg-green-500 rounded-full p-1 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-green-300 font-medium">{{ session('message') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-900/50 border border-red-500 rounded-2xl flex items-center gap-3">
                <div class="bg-red-500 rounded-full p-1 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <span class="text-red-300 font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800/80 border-b border-gray-700/50 text-[10px] uppercase tracking-widest text-gray-400">
                        <th class="p-4 font-bold">Barbero</th>
                        <th class="p-4 font-bold">Rol</th>
                        <th class="p-4 font-bold text-center">Acceso</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @foreach($barbers as $barber)
                        <tr class="hover:bg-gray-750 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-inner">
                                        {{ substr($barber->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-200">{{ $barber->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $barber->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                @if($barber->is_owner)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">Dueño</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-purple-500/10 text-purple-400 border border-purple-500/20">Empleado</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                @if($barber->id === auth()->id())
                                    <span class="text-xs text-gray-500 italic">Tú</span>
                                @else
                                    <button class="text-red-400 hover:text-red-300 text-xs font-bold transition-colors">Remover</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Banner de Ventas (Per-Seat Pricing) -->
    <div class="mt-6 bg-gradient-to-r from-purple-900/40 to-indigo-900/40 border border-purple-500/30 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4 relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="relative z-10">
            <h3 class="text-lg font-black text-white">¿Tu equipo está creciendo? 🚀</h3>
            <p class="text-sm text-gray-300 mt-1">Contacta a <b>Codex Solutions</b> para agregar nuevos asientos y licencias para tu personal.</p>
        </div>
        <a href="#" class="relative z-10 shrink-0 bg-purple-600 hover:bg-purple-500 text-white font-black px-6 py-3 rounded-xl transition-transform hover:-translate-y-0.5 shadow-[0_4px_15px_rgba(147,51,234,0.4)]">
            Adquirir Licencia
        </a>
    </div>
</div>
