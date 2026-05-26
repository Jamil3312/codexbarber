<div class="max-w-6xl mx-auto pb-12">
    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-600">
                Punto de Venta (POS)
            </h1>
            <p class="text-gray-400 text-sm mt-1">Registra la venta de productos, bebidas o accesorios.</p>
        </div>
        <button wire:click="$set('showCreateProduct', true)" class="bg-gray-800 hover:bg-gray-700 text-yellow-500 font-bold py-2.5 px-5 rounded-xl border border-gray-700 transition-all flex items-center gap-2 text-sm shadow-lg hover:shadow-yellow-500/10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Producto
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-900/50 border border-green-500 rounded-2xl flex items-center gap-3">
            <span class="text-green-300 font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-900/50 border border-red-500 rounded-2xl flex items-center gap-3">
            <span class="text-red-300 font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lista de Productos (Izquierda) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.debounce.300ms="search" type="text" class="w-full bg-gray-900 border-gray-800 text-white pl-11 py-3.5 rounded-2xl focus:ring-yellow-500 focus:border-yellow-500 shadow-inner" placeholder="Buscar producto por nombre...">
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @forelse($products as $product)
                    <button wire:click="addToCart({{ $product->id }})" class="bg-gray-900 border border-gray-800 p-4 rounded-3xl hover:border-yellow-500/50 transition-all text-left group relative overflow-hidden h-32 flex flex-col justify-between shadow-lg">
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative z-10">
                            <h3 class="text-gray-200 font-bold leading-tight line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Stock: {{ $product->stock }}</p>
                        </div>
                        <div class="relative z-10 font-black text-yellow-400 text-lg">
                            Q{{ number_format($product->price, 2) }}
                        </div>
                    </button>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500">
                        No hay productos registrados o que coincidan con la búsqueda.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Carrito / Ticket (Derecha) -->
        <div class="bg-gray-900 border border-gray-800 rounded-[2.5rem] shadow-2xl p-6 flex flex-col h-[600px] relative overflow-hidden">
            <h2 class="text-xl font-black text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Venta Actual
            </h2>

            <div class="flex-1 overflow-y-auto space-y-3 pr-2 custom-scrollbar">
                @forelse($cart as $id => $item)
                    <div class="bg-gray-800 rounded-2xl p-3 flex justify-between items-center border border-gray-750">
                        <div class="flex-1 min-w-0 pr-2">
                            <p class="text-sm font-bold text-gray-200 truncate">{{ $item['name'] }}</p>
                            <p class="text-[10px] text-gray-500">Q{{ number_format($item['price'], 2) }} x {{ $item['quantity'] }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <p class="text-sm font-black text-yellow-400">Q{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            <div class="flex items-center gap-2 bg-gray-900 rounded-lg p-0.5 border border-gray-700">
                                <button wire:click="updateQuantity({{ $id }}, 'decrease')" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-white rounded-md hover:bg-gray-700">-</button>
                                <span class="text-xs font-bold text-white w-4 text-center">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $id }}, 'increase')" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-white rounded-md hover:bg-gray-700">+</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-gray-600 opacity-50">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="text-sm font-medium">Ticket Vacío</p>
                    </div>
                @endforelse
            </div>

            <!-- Totales y Botón Cobrar -->
            <div class="pt-4 mt-4 border-t border-gray-800">
                <div class="flex justify-between items-end mb-4">
                    <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Total Venta</span>
                    <span class="text-3xl font-black text-white leading-none">Q{{ number_format($total, 2) }}</span>
                </div>
                <button wire:click="checkout" wire:loading.attr="disabled" class="w-full bg-yellow-500 hover:bg-yellow-400 disabled:opacity-50 text-gray-900 font-black py-4 rounded-2xl transition-all shadow-[0_8px_20px_rgba(234,179,8,0.3)] hover:-translate-y-0.5 text-lg flex justify-center items-center gap-2">
                    <span wire:loading.remove wire:target="checkout">Cobrar</span>
                    <span wire:loading wire:target="checkout">Procesando...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Crear Producto -->
    @if($showCreateProduct)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-950/90 backdrop-blur-sm" wire:click="$set('showCreateProduct', false)"></div>
        <div class="relative bg-gray-900 border border-gray-700 rounded-[2.5rem] shadow-2xl w-full max-w-md p-8 z-10 border-t-4 border-yellow-500">
            <h2 class="text-2xl font-black text-white mb-2">Nuevo Producto</h2>
            <p class="text-xs text-gray-500 mb-6">Agrega un producto a tu inventario para poder venderlo.</p>

            <form wire:submit.prevent="saveProduct" class="space-y-4">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Nombre del Producto</label>
                    <input type="text" wire:model="newProductName" class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-yellow-500 focus:border-yellow-500" placeholder="Ej. Cera Mate Suavecito">
                    @error('newProductName') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Precio (Q)</label>
                        <input type="number" step="0.01" wire:model="newProductPrice" class="w-full bg-gray-800 border-gray-700 text-yellow-400 font-black text-sm rounded-xl focus:ring-yellow-500 focus:border-yellow-500" placeholder="0.00">
                        @error('newProductPrice') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Stock Inicial</label>
                        <input type="number" wire:model="newProductStock" class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-yellow-500 focus:border-yellow-500" placeholder="0">
                        @error('newProductStock') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" wire:click="$set('showCreateProduct', false)" class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-2xl font-bold transition-colors text-sm">Cancelar</button>
                    <button type="submit" wire:loading.attr="disabled" class="flex-1 py-3 bg-yellow-500 hover:bg-yellow-400 disabled:opacity-50 text-gray-900 font-black rounded-2xl transition-colors text-sm">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
