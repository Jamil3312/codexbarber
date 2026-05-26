<div class="max-w-4xl mx-auto bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl p-6 text-white mt-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-gray-800 pb-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600">
                Catálogo de Servicios
            </h1>
            <p class="text-gray-400 text-sm mt-1">Define los cortes y tratamientos que ofreces, sus precios y duraciones.</p>
        </div>
        <button wire:click="create()" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold px-4 py-2 rounded-xl transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nuevo Servicio
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-900/50 border border-green-500 rounded-2xl">
            <span class="text-green-300 font-medium text-sm">{{ session('message') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-5 shadow-lg relative overflow-hidden group">
                <div class="absolute inset-y-0 left-0 w-1 bg-yellow-500 rounded-l-2xl"></div>
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-100">{{ $service->name }}</h3>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="edit({{ $service->id }})" class="text-gray-400 hover:text-yellow-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                        <button onclick="confirm('¿Borrar servicio?') || event.stopImmediatePropagation()" wire:click="delete({{ $service->id }})" class="text-gray-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-yellow-500 font-bold mb-1">
                    <span>Q</span><span>{{ number_format($service->price, 2) }}</span>
                </div>
                <div class="text-xs text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $service->duration_minutes }} minutos
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 text-gray-500 font-medium">
                No has agregado ningún servicio aún.
            </div>
        @endforelse
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-700">
                <form wire:submit.prevent="store">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-xl leading-6 font-bold text-white mb-4" id="modal-title">
                            {{ $service_id ? 'Editar Servicio' : 'Nuevo Servicio' }}
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400">Nombre del Corte/Servicio</label>
                                <input type="text" wire:model="name" class="mt-1 block w-full bg-gray-900 border border-gray-700 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3">
                                @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-400">Precio (Q)</label>
                                    <input type="number" step="0.01" wire:model="price" class="mt-1 block w-full bg-gray-900 border border-gray-700 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3">
                                    @error('price') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium text-gray-400">Duración (minutos)</label>
                                    <input type="number" wire:model="duration_minutes" class="mt-1 block w-full bg-gray-900 border border-gray-700 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 p-3">
                                    @error('duration_minutes') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-700">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-base font-bold text-gray-900 hover:bg-yellow-400 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button type="button" wire:click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-600 shadow-sm px-4 py-2 bg-transparent text-base font-medium text-gray-300 hover:text-white sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
