<div class="py-10 bg-gray-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-600">
                    Codex Solutions Admin
                </h1>
                <p class="text-gray-500 mt-1 text-sm">Control global de inquilinos y suscripciones SaaS</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="openCreateUserModal"
                        class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-purple-400 font-black px-5 py-2.5 rounded-2xl shadow-lg border border-gray-700 hover:-translate-y-0.5 transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Nuevo Asiento
                </button>
                <button wire:click="openCreateModal"
                        class="flex items-center gap-2 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-black px-5 py-2.5 rounded-2xl shadow-[0_5px_20px_rgba(234,179,8,0.3)] hover:-translate-y-0.5 transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Nueva Barbería
                </button>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('message'))
            <div class="mb-5 p-4 bg-green-500/10 border border-green-500/30 text-green-400 text-sm font-bold rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('message') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-5 p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-bold rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ===== TABLA BARBERÍAS ===== --}}
        <div class="bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-800/60 text-gray-500 text-[10px] uppercase font-black tracking-widest">
                            <th class="p-4 border-b border-gray-800">Barbería</th>
                            <th class="p-4 border-b border-gray-800">Métricas</th>
                            <th class="p-4 border-b border-gray-800">Suscripción</th>
                            <th class="p-4 border-b border-gray-800">Estado</th>
                            <th class="p-4 border-b border-gray-800 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/40">
                        @forelse($barbershops as $shop)
                            <tr class="hover:bg-gray-800/20 transition-colors">

                                {{-- Nombre --}}
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-gray-900 text-lg shadow-md flex-shrink-0"
                                             style="background-color: {{ $shop->primary_color ?? '#eab308' }}">
                                            {{ substr($shop->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-white font-bold text-sm">{{ $shop->name }}</p>
                                            <a href="{{ route('tenant.landing', $shop->slug) }}" target="_blank"
                                               class="text-[11px] text-gray-600 hover:text-yellow-500 transition-colors">/b/{{ $shop->slug }}</a>
                                            @php
                                                $planNames = ['basic' => 'Street', 'pro' => 'Studio', 'elite' => 'Empire'];
                                                $planColors = [
                                                    'basic' => 'bg-gray-800 text-gray-400 border-gray-700',
                                                    'pro' => 'bg-blue-900/30 text-blue-400 border-blue-500/30',
                                                    'elite' => 'bg-purple-900/30 text-purple-400 border-purple-500/30'
                                                ];
                                                $currentPlan = $shop->plan_type ?? 'basic';
                                            @endphp
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black uppercase border {{ $planColors[$currentPlan] }}">
                                                {{ $planNames[$currentPlan] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Métricas --}}
                                <td class="p-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[10px]">👤</span>
                                            <span class="text-xs font-bold text-gray-200">{{ $shop->clients_count }}
                                                <span class="font-normal text-gray-500">{{ $shop->clients_count === 1 ? 'cliente' : 'clientes' }}</span>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[10px]">📅</span>
                                            <span class="text-xs text-yellow-500 font-bold">{{ $shop->monthly_appointments_count }}
                                                <span class="font-normal text-gray-500">este mes</span>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[10px]">📊</span>
                                            <span class="text-xs text-gray-500">{{ $shop->appointments_count }} total</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Suscripción --}}
                                <td class="p-4">
                                    @if($shop->subscription_starts_at)
                                        <p class="text-[10px] text-gray-600 uppercase font-bold tracking-wider mb-0.5">
                                            {{ $shop->subscription_type === 'yearly' ? 'Anual' : 'Mensual' }}
                                        </p>
                                        <p class="text-xs text-gray-400 font-bold">
                                            Inició: {{ $shop->subscription_starts_at->format('d/m/Y') }}
                                        </p>
                                        @if($shop->paid_until)
                                            @php
                                                $daysLeft = (int) now()->diffInDays($shop->paid_until, false);
                                                $color = $daysLeft < 0 ? 'text-red-400' : ($daysLeft <= 5 ? 'text-yellow-500' : 'text-gray-300');
                                            @endphp
                                            <p class="text-xs {{ $color }} font-semibold">
                                                Vence: {{ $shop->paid_until->format('d/m/Y') }}
                                            </p>
                                            @if($daysLeft >= 0 && $daysLeft <= 5)
                                                <span class="text-[10px] text-yellow-500 font-black">⚠️ Vence en {{ $daysLeft }}d</span>
                                            @elseif($daysLeft < 0)
                                                <span class="text-[10px] text-red-500 font-black">Vencida hace {{ abs($daysLeft) }}d</span>
                                            @endif
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-600 italic">Sin periodo definido</span>
                                    @endif
                                    @if($shop->grace_days)
                                        <p class="text-[10px] text-gray-700 mt-1">Gracia: {{ $shop->grace_days }} días</p>
                                    @endif
                                </td>

                                {{-- Estado --}}
                                <td class="p-4">
                                    @if($shop->subscription_status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-green-500/10 text-green-400 border border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Al Día
                                        </span>
                                    @elseif($shop->subscription_status === 'blocked')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-orange-500/10 text-orange-400 border border-orange-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Bloqueada
                                        </span>
                                        @if($shop->block_reason)
                                            <p class="text-[10px] text-orange-400/70 mt-1 max-w-[140px] leading-tight">{{ $shop->block_reason }}</p>
                                        @endif
                                        @if($shop->blocked_at)
                                            <p class="text-[10px] text-gray-600 mt-0.5">{{ $shop->blocked_at->diffForHumans() }}</p>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-red-500/10 text-red-400 border border-red-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Suspendida
                                        </span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="p-4">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        {{-- Editar Color --}}
                                        <button wire:click="openEditColorModal({{ $shop->id }})"
                                                class="px-2.5 py-1.5 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-xl text-[10px] font-black transition-colors flex items-center gap-1"
                                                title="Cambiar Color de Marca">
                                            🎨 Color
                                        </button>

                                        {{-- Gestionar Suscripción --}}
                                        <button wire:click="openSubModal({{ $shop->id }})"
                                                class="px-2.5 py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white border border-gray-700 rounded-xl text-[10px] font-black transition-colors flex items-center gap-1"
                                                title="Gestionar Período">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Período
                                        </button>

                                        {{-- +1 Mes --}}
                                        <button wire:click="extendSubscription({{ $shop->id }}, 1)"
                                                class="px-2.5 py-1.5 bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 rounded-xl text-[10px] font-black transition-colors">
                                            +1 Mes
                                        </button>

                                        {{-- +1 Año --}}
                                        <button wire:click="extendSubscription({{ $shop->id }}, 12)"
                                                class="px-2.5 py-1.5 bg-green-500/10 hover:bg-green-500/20 text-green-400 border border-green-500/30 rounded-xl text-[10px] font-black transition-colors">
                                            +1 Año
                                        </button>

                                        {{-- Bloquear / Desbloquear --}}
                                        @if($shop->subscription_status === 'blocked')
                                            <button wire:click="unblockBarbershop({{ $shop->id }})"
                                                    class="p-1.5 bg-orange-500/10 hover:bg-orange-500/20 text-orange-400 rounded-xl transition-colors border border-orange-500/20"
                                                    title="Desbloquear">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                            </button>
                                        @elseif($shop->subscription_status === 'active')
                                            <button wire:click="openBlockModal({{ $shop->id }})"
                                                    class="p-1.5 bg-gray-800 hover:bg-orange-500/10 text-gray-500 hover:text-orange-400 rounded-xl transition-colors border border-gray-700 hover:border-orange-500/30"
                                                    title="Bloquear manualmente">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zM10 11V7a4 4 0 118 0v4"></path></svg>
                                            </button>
                                        @endif

                                        {{-- Suspender (si está activa) --}}
                                        @if($shop->subscription_status === 'active')
                                            <button wire:click="suspendBarbershop({{ $shop->id }})"
                                                    onclick="return confirm('¿Suspender {{ $shop->name }}? Perderán acceso inmediatamente.')"
                                                    class="p-1.5 bg-gray-800 hover:bg-yellow-500/10 text-gray-500 hover:text-yellow-400 rounded-xl transition-colors border border-gray-700 hover:border-yellow-500/30"
                                                    title="Suspender cuenta">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                            </button>
                                        @endif

                                        {{-- Eliminar Definitivamente --}}
                                        <button onclick="if(!confirm('ESTA ACCIÓN ES IRREVERSIBLE. ¿Estás absolutamente seguro de que quieres eliminar \'{{ $shop->name }}\', todos sus usuarios, configuraciones y citas por completo? NO habrá forma de recuperar estos datos.')) { event.stopImmediatePropagation(); return false; }"
                                                wire:click="deleteBarbershop({{ $shop->id }})"
                                                class="p-1.5 bg-red-900/20 hover:bg-red-600 text-red-500 hover:text-white rounded-xl transition-all border border-red-900/40 hover:border-red-500 hover:scale-105 shadow-sm"
                                                title="Eliminar Barbería por completo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-16 text-center text-gray-600">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <p class="font-bold text-sm">Sin barberías registradas</p>
                                    <p class="text-xs mt-1">Crea la primera usando el botón "Nueva Barbería"</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        {{-- ===== MODAL: NUEVA BARBERÍA + USUARIO SOCIO ===== --}}
        @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div class="absolute inset-0 bg-gray-950/90 backdrop-blur-sm" wire:click="$set('showCreateModal', false)"></div>
            <div class="relative bg-gray-900 border border-gray-700 rounded-[2rem] shadow-2xl w-full max-w-lg my-8 z-10 border-t-4 border-yellow-500 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-black text-white mb-1">➕ Nueva Barbería</h2>
                    <p class="text-xs text-gray-500 mb-5">Crea la barbería y el acceso del socio en un solo paso.</p>

                    {{-- === Datos de la Barbería === --}}
                    <p class="text-[10px] text-yellow-500 font-black uppercase tracking-widest mb-3">📋 Datos de la Barbería</p>
                    <div class="space-y-3 mb-5">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Nombre</label>
                            <input type="text" wire:model.live="newName" placeholder="Ej. RD Barbería Premium"
                                   class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-yellow-500 focus:border-yellow-500">
                            @error('newName') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Identificador URL</label>
                            <div class="flex items-center bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
                                <span class="px-3 text-gray-600 text-xs font-bold select-none border-r border-gray-700 py-2.5">/b/</span>
                                <input type="text" wire:model="newSlug" placeholder="rd-barberia"
                                       class="flex-1 bg-transparent text-white text-sm py-2.5 px-3 border-none focus:ring-0 outline-none">
                            </div>
                            @error('newSlug') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Color de Marca</label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model="newColor" class="w-12 h-10 rounded-lg border border-gray-700 bg-gray-800 cursor-pointer">
                                <span class="text-sm text-gray-400 font-mono">{{ $newColor }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Separador --}}
                    <div class="border-t border-gray-800 my-4"></div>

                    {{-- === Datos del Socio (Barbero) === --}}
                    <p class="text-[10px] text-blue-400 font-black uppercase tracking-widest mb-3">👤 Cuenta del Socio</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Nombre Completo</label>
                            <input type="text" wire:model="ownerName" placeholder="Ej. Ricardo Díaz"
                                   class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            @error('ownerName') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Correo Electrónico</label>
                            <input type="email" wire:model="ownerEmail" placeholder="socio@barberia.com"
                                   class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            @error('ownerEmail') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">
                                Contraseña Inicial
                                <span class="text-gray-600 font-normal normal-case ml-1">(auto-generada, puedes modificarla)</span>
                            </label>
                            <input type="text" wire:model="ownerPassword"
                                   class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 font-mono">
                            @error('ownerPassword') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button wire:click="$set('showCreateModal', false)"
                                class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-2xl font-bold transition-colors text-sm">
                            Cancelar
                        </button>
                        <button wire:click="createBarbershop" wire:loading.attr="disabled"
                                class="flex-1 py-3 bg-yellow-500 hover:bg-yellow-400 disabled:opacity-50 text-gray-900 font-black rounded-2xl transition-colors text-sm">
                            <span wire:loading.remove>Crear Barbería y Socio</span>
                            <span wire:loading>Creando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ===== MODAL: CREDENCIALES GENERADAS ===== --}}
        @if($showCredentials && count($generatedCreds))
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-950/90 backdrop-blur-sm"></div>
            <div class="relative bg-gray-900 border border-green-500/30 rounded-[2rem] shadow-2xl w-full max-w-md p-6 z-10 border-t-4 border-green-500">
                <h2 class="text-xl font-black text-green-400 mb-1">✅ ¡Barbería Creada!</h2>
                <p class="text-xs text-gray-500 mb-5">Guarda o comparte estas credenciales con el socio. <strong class="text-red-400">Esta pantalla aparece una sola vez.</strong></p>

                <div class="bg-gray-800 rounded-2xl p-4 space-y-3 text-sm border border-gray-700">
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Barbería</p>
                        <p class="font-bold text-white">{{ $generatedCreds['barbershop'] }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">URL de Acceso</p>
                        <a href="{{ $generatedCreds['url'] }}" target="_blank" class="text-yellow-500 font-mono text-xs hover:underline break-all">{{ $generatedCreds['url'] }}</a>
                    </div>
                    <div class="border-t border-gray-700 pt-3">
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Correo</p>
                        <p class="font-mono text-white">{{ $generatedCreds['email'] }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Contraseña</p>
                        <p class="font-mono text-lg font-black text-yellow-400 tracking-widest">{{ $generatedCreds['password'] }}</p>
                    </div>
                </div>

                <p class="text-[10px] text-gray-600 mt-3 text-center">El socio puede cambiar su contraseña desde su perfil después de iniciar sesión.</p>

                <button wire:click="$set('showCredentials', false)"
                        class="w-full mt-4 py-3 bg-green-500 hover:bg-green-400 text-gray-900 font-black rounded-2xl transition-colors text-sm">
                    Entendido, ya copié las credenciales
                </button>
            </div>
        </div>
        @endif


        {{-- ===== MODAL: GESTIONAR SUSCRIPCIÓN ===== --}}
        @if($showSubModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-950/90 backdrop-blur-sm" wire:click="$set('showSubModal', false)"></div>
            <div class="relative bg-gray-900 border border-gray-700 rounded-[2rem] shadow-2xl w-full max-w-md p-6 z-10 border-t-4 border-blue-500">
                <h2 class="text-xl font-black text-white mb-1">📅 Gestionar Período</h2>
                <p class="text-xs text-gray-500 mb-5">Define cuándo inicia y finaliza la suscripción del socio.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Fecha de Inicio del Período</label>
                        <input type="date" wire:model="subStartDate"
                               class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500">
                        @error('subStartDate') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Nivel del Plan (Tier)</label>
                        <select wire:model="planType" class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500">
                            <option value="basic">Street (Básico)</option>
                            <option value="pro">Studio (Profesional)</option>
                            <option value="elite">Empire (Élite)</option>
                        </select>
                        @error('planType') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-2">Tipo de Suscripción</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="subType" value="monthly" class="sr-only peer">
                                <div class="p-3 text-center rounded-2xl border-2 transition-all border-gray-700 bg-gray-800 text-gray-400 text-sm font-bold peer-checked:border-blue-500 peer-checked:bg-blue-500/10 peer-checked:text-blue-400">
                                    📆 Mensual<br><span class="text-[10px] font-normal text-gray-500">+1 mes</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="subType" value="yearly" class="sr-only peer">
                                <div class="p-3 text-center rounded-2xl border-2 transition-all border-gray-700 bg-gray-800 text-gray-400 text-sm font-bold peer-checked:border-green-500 peer-checked:bg-green-500/10 peer-checked:text-green-400">
                                    🏆 Anual<br><span class="text-[10px] font-normal text-gray-500">+12 meses</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Preview date --}}
                    @if($subStartDate)
                    @php
                        $previewExpiry = \Carbon\Carbon::parse($subStartDate);
                        $previewExpiry = $subType === 'yearly' ? $previewExpiry->addYear() : $previewExpiry->addMonth();
                    @endphp
                    <div class="p-3 bg-gray-800/60 border border-gray-700 rounded-xl">
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-0.5">Vencerá el</p>
                        <p class="text-sm font-black text-white">{{ $previewExpiry->translatedFormat('d \d\e F, Y') }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Notas Internas (Opcional)</label>
                        <textarea wire:model="adminNotes" rows="2" placeholder="Ej. Primer pago recibido por transferencia BI..."
                                  class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="$set('showSubModal', false)"
                            class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-2xl font-bold transition-colors text-sm">
                        Cancelar
                    </button>
                    <button wire:click="saveSubscription"
                            class="flex-1 py-3 bg-blue-500 hover:bg-blue-400 text-white font-black rounded-2xl transition-colors text-sm">
                        Guardar Período
                    </button>
                </div>
            </div>
        </div>
        @endif


        {{-- ===== MODAL: BLOQUEO MANUAL ===== --}}
        @if($showBlockModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-950/90 backdrop-blur-sm" wire:click="$set('showBlockModal', false)"></div>
            <div class="relative bg-gray-900 border border-orange-500/30 rounded-[2rem] shadow-2xl w-full max-w-md p-6 z-10 border-t-4 border-orange-500">
                <h2 class="text-xl font-black text-orange-400 mb-1">🔒 Bloqueo Manual</h2>
                <p class="text-xs text-gray-500 mb-5">El socio verá el motivo en su pantalla de bloqueo. Sé claro y profesional.</p>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Motivo del Bloqueo *</label>
                    <textarea wire:model="blockReason" rows="3"
                              placeholder="Ej. Cuenta bloqueada temporalmente por incumplimiento de términos de servicio. Contacte a soporte para regularizar."
                              class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-orange-500 focus:border-orange-500 resize-none">
                    </textarea>
                    @error('blockReason') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="p-3 bg-orange-500/5 border border-orange-500/20 rounded-xl mt-4">
                    <p class="text-xs text-orange-400/80">⚠️ El acceso al sistema se bloqueará de inmediato. Los clientes del socio no podrán ver su agenda mientras esté bloqueado.</p>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="$set('showBlockModal', false)"
                            class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-2xl font-bold transition-colors text-sm">
                        Cancelar
                    </button>
                    <button wire:click="blockBarbershop"
                            class="flex-1 py-3 bg-orange-500 hover:bg-orange-400 text-white font-black rounded-2xl transition-colors text-sm">
                        Confirmar Bloqueo
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- ===== MODAL: NUEVA LICENCIA/ASIENTO ===== --}}
        @if($showCreateUserModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-950/90 backdrop-blur-sm" wire:click="$set('showCreateUserModal', false)"></div>
            <div class="relative bg-gray-900 border border-gray-700 rounded-[2rem] shadow-2xl w-full max-w-lg p-6 z-10 border-t-4 border-indigo-500 overflow-y-auto max-h-[90vh]">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-black text-white leading-none">Nueva Licencia</h2>
                        <p class="text-xs text-gray-500 mt-1">Crea un usuario para una barbería existente.</p>
                    </div>
                </div>

                <form wire:submit.prevent="createUser" class="space-y-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Barbería Destino</label>
                        <select wire:model="newUserBarbershopId" class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                            <option value="">Seleccione una barbería...</option>
                            @foreach(\App\Models\Barbershop::all() as $bs)
                                <option value="{{ $bs->id }}">{{ $bs->name }} (Plan: {{ $bs->plan_type }})</option>
                            @endforeach
                        </select>
                        @error('newUserBarbershopId') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Nombre Completo</label>
                            <input type="text" wire:model="newUserName" class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej. Carlos Ruiz">
                            @error('newUserName') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Correo Electrónico</label>
                            <input type="email" wire:model="newUserEmail" class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500" placeholder="carlos@barberia.com">
                            @error('newUserEmail') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Contraseña</label>
                            <input type="text" wire:model="newUserPassword" class="w-full bg-gray-800 border-gray-700 text-indigo-400 font-mono text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                            @error('newUserPassword') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-1">Rol / Permisos</label>
                            <select wire:model="newUserRole" class="w-full bg-gray-800 border-gray-700 text-white text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-3">
                                <option value="employee">Empleado (Solo ve sus citas/finanzas)</option>
                                <option value="owner">Dueño/Admin (Ve todo el local)</option>
                            </select>
                            @error('newUserRole') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" wire:click="$set('showCreateUserModal', false)" class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-2xl font-bold transition-colors text-sm">Cancelar</button>
                        <button type="submit" wire:loading.attr="disabled" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white font-black rounded-2xl transition-colors text-sm">
                            <span wire:loading.remove wire:target="createUser">Crear Licencia</span>
                            <span wire:loading wire:target="createUser">Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- ===== MODAL: EDITAR COLOR ===== --}}
        @if($showEditColorModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-gray-950/90 backdrop-blur-sm" wire:click="$set('showEditColorModal', false)"></div>
            <div class="relative bg-gray-900 border border-gray-700 rounded-[2rem] shadow-2xl w-full max-w-sm p-6 z-10 border-t-4 border-indigo-500">
                <h2 class="text-xl font-black text-white mb-1">🎨 Cambiar Color</h2>
                <p class="text-xs text-gray-500 mb-5">Actualiza el color principal de la barbería.</p>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-bold mb-2">Color de Marca</label>
                    <div class="flex items-center gap-3">
                        <input type="color" wire:model="editColor" class="w-16 h-12 rounded-lg border border-gray-700 bg-gray-800 cursor-pointer shadow-inner">
                        <span class="text-lg text-gray-300 font-mono font-bold">{{ $editColor }}</span>
                    </div>
                    @error('editColor') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3 mt-8">
                    <button wire:click="$set('showEditColorModal', false)"
                            class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-2xl font-bold transition-colors text-sm">
                        Cancelar
                    </button>
                    <button wire:click="updateBarbershopColor"
                            class="flex-1 py-3 bg-indigo-500 hover:bg-indigo-400 text-white font-black rounded-2xl transition-colors text-sm shadow-[0_5px_15px_rgba(99,102,241,0.3)] hover:-translate-y-0.5">
                        Guardar Color
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
