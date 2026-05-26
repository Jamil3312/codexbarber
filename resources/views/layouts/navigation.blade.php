<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800 shadow-lg relative z-20">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()->is_superadmin ? route('superadmin.dashboard') : route('dashboard') }}" class="flex items-center gap-2">
                        <svg class="block h-9 w-auto" style="color: var(--primary-color)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"></path></svg>
                        <span class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-600 tracking-wider">
                            {{ Auth::user()->is_superadmin ? 'Barber System' : (Auth::user()->barbershop->name ?? 'RD Barbería') }}
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if(!Auth::user()->is_superadmin && !Auth::user()->is_barber && Auth::user()->barbershop_id)
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Reservar') }}
                        </x-nav-link>
                    @endif
                    
                    @if(Auth::user()->is_superadmin)
                        <x-nav-link :href="url('/superadmin/dashboard')" :active="request()->is('superadmin/dashboard')">
                            {{ __('Codex SaaS') }}
                        </x-nav-link>
                    @endif
                    @if(Auth::user()->is_barber)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Panel Profesional') }}
                        </x-nav-link>

                        {{-- Solo el dueño de la barbería puede ver la configuración general --}}
                        @if(Auth::user()->is_owner)
                            <x-nav-link :href="route('admin.services')" :active="request()->routeIs('admin.services')">
                                {{ __('Servicios') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                                {{ __('Horarios') }}
                            </x-nav-link>
                        @endif

                        {{-- Premium Feature Gates --}}
                        @php
                            $plan = Auth::user()->barbershop->plan_type ?? 'basic';
                        @endphp

                        {{-- Punto de Venta (Libre en Studio y Empire) - Visible para Empleados y Dueños --}}
                        @if($plan === 'basic')
                            <div class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 opacity-50 cursor-not-allowed group relative" title="Función Pro: POS">
                                🔒 Punto de Venta
                                <div class="absolute bottom-[-30px] left-1/2 transform -translate-x-1/2 hidden group-hover:block w-max bg-gray-900 text-[10px] text-white px-2 py-1 rounded shadow-lg z-50">
                                    Mejora a plan Studio para vender productos
                                </div>
                            </div>
                        @else
                            <x-nav-link :href="route('admin.pos')" :active="request()->routeIs('admin.pos')" class="text-yellow-400">
                                {{ __('Punto de Venta') }}
                            </x-nav-link>
                        @endif

                        {{-- Módulos de Administración de Personal (Solo Dueños) --}}
                        @if(Auth::user()->is_owner)
                            {{-- Equipo (Libre en Empire) --}}
                            @if(in_array($plan, ['basic', 'pro']))
                                <div class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 opacity-50 cursor-not-allowed group relative" title="Función Élite: Multibarbero">
                                    🔒 Equipo
                                    <div class="absolute bottom-[-30px] left-1/2 transform -translate-x-1/2 hidden group-hover:block w-max bg-gray-900 text-[10px] text-white px-2 py-1 rounded shadow-lg z-50">
                                        Mejora a plan Elite para añadir barberos
                                    </div>
                                </div>
                            @else
                                <x-nav-link :href="route('admin.team')" :active="request()->routeIs('admin.team')" class="text-purple-400">
                                    {{ __('Equipo') }}
                                </x-nav-link>
                            @endif

                            {{-- Comisiones y Reportes (Libre en Empire) --}}
                            @if(in_array($plan, ['basic', 'pro']))
                                <div class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 opacity-50 cursor-not-allowed group relative" title="Función Élite: Comisiones">
                                    🔒 Comisiones
                                    <div class="absolute bottom-[-30px] left-1/2 transform -translate-x-1/2 hidden group-hover:block w-max bg-gray-900 text-[10px] text-white px-2 py-1 rounded shadow-lg z-50">
                                        Mejora a plan Elite para ver comisiones
                                    </div>
                                </div>
                            @else
                                <x-nav-link :href="route('admin.commissions')" :active="request()->routeIs('admin.commissions')" class="text-blue-400">
                                    {{ __('Comisiones') }}
                                </x-nav-link>
                            @endif
                        @endif
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- PWA Install Button (Desktop) -->
                <button id="installAppBtnDesktop" onclick="installPWA()" class="hidden inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-xl font-bold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-400 focus:outline-none transition ease-in-out duration-150 mr-4 shadow-lg shadow-yellow-500/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Instalar App
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-700 text-sm leading-4 font-medium rounded-xl text-gray-300 bg-gray-800 hover:text-white transition ease-in-out duration-150" style="border-color: transparent;" onmouseover="this.style.borderColor='var(--primary-color)'" onmouseout="this.style.borderColor='transparent'">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out" :style="open ? 'color: var(--primary-color)' : ''">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(!Auth::user()->is_superadmin && !Auth::user()->is_barber && Auth::user()->barbershop_id)
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Reservar') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->is_superadmin)
                <x-responsive-nav-link :href="url('/superadmin/dashboard')" :active="request()->is('superadmin/dashboard')">
                    {{ __('Codex SaaS') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->is_barber)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Panel Profesional') }}
                </x-responsive-nav-link>

                @php
                    $plan = Auth::user()->barbershop->plan_type ?? 'basic';
                @endphp

                @if($plan !== 'basic')
                    <x-responsive-nav-link :href="route('admin.pos')" :active="request()->routeIs('admin.pos')" class="text-yellow-400">
                        {{ __('Punto de Venta') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->is_owner)
                    <x-responsive-nav-link :href="route('admin.services')" :active="request()->routeIs('admin.services')">
                        {{ __('Servicios') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                        {{ __('Horarios') }}
                    </x-responsive-nav-link>

                    @if(!in_array($plan, ['basic', 'pro']))
                        <x-responsive-nav-link :href="route('admin.team')" :active="request()->routeIs('admin.team')" class="text-purple-400">
                            {{ __('Equipo') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.commissions')" :active="request()->routeIs('admin.commissions')" class="text-blue-400">
                            {{ __('Comisiones') }}
                        </x-responsive-nav-link>
                    @endif
                @endif
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-800">
            <div class="px-4">
                <div class="font-medium text-base text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- PWA Install Button (Mobile) -->
                <button id="installAppBtnMobile" onclick="installPWA()" class="hidden w-full text-left pl-3 pr-4 py-2 border-l-4 border-yellow-500 text-base font-medium text-yellow-500 bg-yellow-500/10 hover:bg-yellow-500/20 transition duration-150 ease-in-out">
                    📲 Instalar Aplicación
                </button>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
