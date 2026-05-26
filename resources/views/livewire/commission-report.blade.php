<div class="max-w-6xl mx-auto pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-indigo-400 to-purple-600">
                Reportes y Comisiones
            </h1>
            <p class="text-gray-400 text-sm mt-1">Analiza el rendimiento de tu equipo y calcula pagos.</p>
        </div>
        
        <div class="w-full md:w-auto">
            <input type="date" wire:model="selectedDate" class="w-full md:w-auto bg-gray-900 border border-gray-800 text-white text-sm rounded-xl focus:ring-indigo-500 py-3 px-4 shadow-inner">
        </div>
    </div>

    <!-- Cards de Barberos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reportData as $data)
            <div class="bg-gray-900 border border-gray-800 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden group hover:border-indigo-500/30 transition-colors">
                <!-- Decoración -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-indigo-500/10 transition-colors"></div>
                
                <div class="flex items-center gap-4 mb-6 relative z-10">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-xl shadow-inner border-2 border-gray-800">
                        {{ substr($data['barber']->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white leading-tight">{{ $data['barber']->name }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $data['barber']->is_owner ? 'bg-yellow-500/20 text-yellow-500' : 'bg-gray-800 text-gray-400' }}">
                            {{ $data['barber']->is_owner ? 'Dueño' : 'Empleado' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-6 relative z-10">
                    
                    <!-- --- SECCIÓN DIARIA --- -->
                    <div>
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-800 pb-1">En el día seleccionado</h4>
                        <div class="space-y-2">
                            <!-- Ingresos por Servicios -->
                            <div class="flex justify-between items-center bg-gray-800/30 p-2 rounded-lg border border-gray-800/50">
                                <span class="text-xs text-gray-400">Servicios (Cortes)</span>
                                <span class="text-sm font-bold text-white">Q{{ number_format($data['daily_services'], 2) }}</span>
                            </div>
                            <!-- Ingresos por POS -->
                            <div class="flex justify-between items-center bg-gray-800/30 p-2 rounded-lg border border-gray-800/50">
                                <span class="text-xs text-gray-400">Ventas POS</span>
                                <span class="text-sm font-bold text-white">Q{{ number_format($data['daily_products'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-xs font-bold text-gray-500">Total del Día</span>
                                <span class="text-base font-black text-indigo-400">Q{{ number_format($data['daily_total'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- --- SECCIÓN MENSUAL --- -->
                    <div>
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-800 pb-1">Acumulado del mes</h4>
                        <div class="space-y-2">
                            <!-- Ingresos por Servicios -->
                            <div class="flex justify-between items-center bg-gray-800/30 p-2 rounded-lg border border-gray-800/50">
                                <span class="text-xs text-gray-400">Servicios (Cortes)</span>
                                <span class="text-sm font-bold text-white">Q{{ number_format($data['monthly_services'], 2) }}</span>
                            </div>
                            <!-- Ingresos por POS -->
                            <div class="flex justify-between items-center bg-gray-800/30 p-2 rounded-lg border border-gray-800/50">
                                <span class="text-xs text-gray-400">Ventas POS</span>
                                <span class="text-sm font-bold text-white">Q{{ number_format($data['monthly_products'], 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-xs font-bold text-gray-500">Total del Mes</span>
                                <span class="text-base font-black text-purple-400">Q{{ number_format($data['monthly_total'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 bg-gray-900 border border-gray-800 rounded-[2rem]">
                No hay barberos registrados o datos para este mes.
            </div>
        @endforelse
    </div>
</div>
