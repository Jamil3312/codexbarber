<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $barbershop->name }} - Reserva tu cita</title>
        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
        
        <!-- PWA Setup -->
        @php
            $manifestUrl = asset('manifest.json');
            if (isset($barbershop)) {
                $manifestUrl = route('tenant.manifest', ['slug' => $barbershop->slug]);
            } elseif (session()->has('tenant_slug')) {
                $manifestUrl = route('tenant.manifest', ['slug' => session('tenant_slug')]);
            }
        @endphp
        <link rel="manifest" href="{{ $manifestUrl }}">
        <meta name="theme-color" content="#eab308">
        @php
            $appleIconText = isset($barbershop) ? strtoupper(substr($barbershop->name, 0, 2)) : 'RD';
            $appleColorHex = isset($barbershop) && $barbershop->primary_color ? $barbershop->primary_color : '#eab308';
            if ($appleColorHex === 'yellow-500') $appleColorHex = '#eab308';
            $appleIconColor = str_replace('#', '%23', $appleColorHex);
        @endphp
        <link rel="apple-touch-icon" href="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><rect width='512' height='512' fill='%23030712'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-size='200' font-family='sans-serif' font-weight='bold' fill='{{ $appleIconColor }}'>{{ $appleIconText }}</text></svg>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{{ $appleIconText }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register("{{ asset('sw.js') }}");
                });
            }
        </script>

        <style>
            :root {
                @php
                    $color = $barbershop->primary_color ?? '#eab308';
                    if($color == 'yellow-500') $color = '#eab308';
                @endphp
                --primary-main: {{ \App\Helpers\ColorHelper::hexToRgbString($color) }};
                --primary-light: {{ \App\Helpers\ColorHelper::adjustBrightness($color, 30) }};
                --primary-dark: {{ \App\Helpers\ColorHelper::adjustBrightness($color, -30) }};
                --primary-color: {{ $color }};
                --primary-glow: {{ $color }}4D; /* 30% alpha */
            }
            .text-primary-dynamic { color: var(--primary-color); }
            .bg-primary-dynamic { background-color: var(--primary-color); }
            .border-primary-dynamic { border-color: var(--primary-color); }
            .shadow-primary-dynamic { box-shadow: 0 10px 30px var(--primary-glow); }
        </style>
    </head>
    <body class="antialiased bg-gray-950 text-gray-100 font-sans selection:bg-yellow-500 selection:text-gray-900">
        
        <!-- Hero Section -->
        <main class="relative bg-gray-900 overflow-hidden min-h-screen flex flex-col justify-center">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gray-950 mix-blend-multiply opacity-80 z-10" aria-hidden="true"></div>
                <img src="https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&q=80&w=2000" alt="Barbershop Background" class="w-full h-full object-cover grayscale opacity-40">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-900/40 to-transparent z-10"></div>
            </div>

            <!-- Top Navbar -->
            <nav class="absolute top-0 left-0 right-0 z-50 flex flex-col md:flex-row justify-between items-center p-6 lg:px-12 gap-4">
                <div class="hidden md:block md:w-1/3"></div>
                
                <div class="flex items-center justify-center gap-3 w-full md:w-1/3 md:absolute md:left-1/2 md:top-1/2 md:-translate-x-1/2 md:-translate-y-1/2">
                    <svg class="h-10 md:h-12 w-auto text-primary-dynamic drop-shadow-[0_0_15px_var(--primary-glow)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"></path></svg>
                    <span class="text-3xl md:text-4xl font-black tracking-widest uppercase drop-shadow-[0_0_15px_var(--primary-glow)] text-primary-dynamic">{{ $barbershop->name }}</span>
                </div>

                <div class="w-full md:w-1/3 flex justify-center md:justify-end relative z-10">
                    <div class="flex items-center gap-3">
                        @auth
                            @php
                                $authUser = auth()->user();
                                // Superadmin o barber → mostrar acceso a su panel
                                // Cliente de ESTA barbería → "Ir a mis citas"
                                // Cliente de OTRA barbería → mostrar como visitante (cerrar sesión no es su barbería)
                                $isStaff = $authUser->is_superadmin || $authUser->is_barber;
                                $isClientHere = !$isStaff && $authUser->barbershop_id === $barbershop->id;

                                if ($authUser->is_superadmin) {
                                    $panelUrl = '/superadmin/dashboard';
                                    $panelLabel = 'Panel Admin';
                                } elseif ($authUser->is_barber) {
                                    $panelUrl = route('admin.dashboard');
                                    $panelLabel = 'Mi Panel';
                                } else {
                                    $panelUrl = url('/dashboard');
                                    $panelLabel = 'Mis Citas';
                                }
                            @endphp
                            <a href="{{ $panelUrl }}" class="font-bold text-gray-300 hover:text-white border px-5 py-2 border-gray-700 rounded-full bg-gray-800/50 backdrop-blur transition hover:border-yellow-500 text-sm">
                                {{ $panelLabel }}
                            </a>
                        @else
                            {{-- Visitante: botones de acceso al tenant específico --}}
                            <a href="{{ route('login') }}?from={{ $barbershop->slug }}"
                               class="font-semibold text-gray-300 hover:text-white transition text-sm">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}?from={{ $barbershop->slug }}"
                                   class="font-bold text-gray-900 px-5 py-2 rounded-full shadow-lg transition hover:-translate-y-0.5 text-sm"
                                   style="background-color: var(--primary-color); box-shadow: 0 0 20px var(--primary-glow);">
                                    Crear Cuenta
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </nav>

            <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center mt-20">
                <span class="inline-block py-1 px-3 rounded-full bg-gray-800/80 border border-gray-700 text-primary-dynamic font-semibold text-sm tracking-widest mb-6 backdrop-blur" style="color: var(--primary-color)">EXCELENCIA Y PRECISIÓN</span>
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white tracking-tight mb-8 leading-tight">
                    Tu estilo en <br/> <span class="text-transparent bg-clip-text" style="background-image: linear-gradient(to right, #fff, var(--primary-color), #fff)">manos de expertos.</span>
                </h1>
                <p class="mt-4 max-w-2xl text-lg md:text-xl text-gray-300 mb-10 leading-relaxed font-medium">
                    No es solo un corte de cabello, es una experiencia premium. Reserva tu lugar hoy mismo desde tu celular y sin filas.
                </p>
                <div class="flex flex-col sm:flex-row gap-5 w-full sm:w-auto">
                    @auth
                        @if(auth()->user()->is_superadmin || auth()->user()->is_barber)
                            {{-- Staff autenticado: NO mostrar botón de agendar, mostrar acceso a panel --}}
                            <a href="{{ $panelUrl }}" class="w-full sm:w-auto flex justify-center items-center gap-2 text-gray-900 px-8 py-4 rounded-2xl font-black text-lg shadow-xl transition-all transform hover:-translate-y-1"
                               style="background-color: var(--primary-color); box-shadow: 0 15px 35px var(--primary-glow);">
                                Ir a {{ $panelLabel }}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @else
                            {{-- Cliente autenticado: sí puede agendar --}}
                            <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto flex justify-center items-center gap-2 text-gray-900 px-8 py-4 rounded-2xl font-black text-lg shadow-xl transition-all transform hover:-translate-y-1"
                               style="background-color: var(--primary-color); box-shadow: 0 15px 35px var(--primary-glow);">
                                Agendar Cita Ahora
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endif
                    @else
                        {{-- Visitante: va al login del tenant --}}
                        <a href="{{ route('login') }}?from={{ $barbershop->slug }}" class="w-full sm:w-auto flex justify-center items-center gap-2 text-gray-900 px-8 py-4 rounded-2xl font-black text-lg shadow-xl transition-all transform hover:-translate-y-1"
                           style="background-color: var(--primary-color); box-shadow: 0 15px 35px var(--primary-glow);">
                            Agendar Cita Ahora
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @endauth
                </div>
                
                <div class="mt-20 grid grid-cols-3 gap-8 md:gap-16 border-t border-gray-800 pt-10">
                    <div>
                        <h4 class="text-3xl font-black mb-1" style="color: var(--primary-color)">5+</h4>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Años de Exp.</p>
                    </div>
                    <div>
                        <h4 class="text-3xl font-black text-white mb-1"><svg class="w-8 h-8 inline" style="color: var(--primary-color)" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> 4.9</h4>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Reseñas</p>
                    </div>
                    <div>
                        <h4 class="text-3xl font-black mb-1" style="color: var(--primary-color)">100%</h4>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Digital</p>
                    </div>
                </div>
            </div>
        </main>
        
        <style>
            @media (min-width: 1024px) {
                h1 { line-height: 1.1 !important; }
            }
        </style>
    </body>
</html>
