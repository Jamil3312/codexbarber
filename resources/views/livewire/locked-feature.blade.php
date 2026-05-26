<div class="max-w-3xl mx-auto py-12 px-4">
    <div class="bg-gray-900 border border-gray-800 rounded-[2.5rem] p-10 text-center shadow-2xl relative overflow-hidden">
        <!-- Decoración -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center">
            <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center border-4 border-gray-700 shadow-inner mb-6">
                <span class="text-4xl">🔒</span>
            </div>
            
            <h2 class="text-3xl font-black text-white mb-3">Función Bloqueada</h2>
            <p class="text-gray-400 text-lg mb-8 max-w-lg mx-auto">
                El módulo de <strong class="text-yellow-500">{{ $featureName }}</strong> es una función premium. Necesitas subir tu suscripción al plan <strong class="text-white">{{ $requiredPlan }}</strong> para acceder.
            </p>

            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-black px-6 py-3 rounded-2xl transition-all shadow-[0_8px_20px_rgba(234,179,8,0.3)] hover:-translate-y-0.5">
                Volver al Panel
            </a>
        </div>
    </div>
</div>
