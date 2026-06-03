<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
        <link rel="apple-touch-icon" href="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><rect width='512' height='512' fill='%23030712'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-size='200' font-family='sans-serif' font-weight='bold' fill='%23eab308'>RD</text></svg>">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        </script>

        <style>
            :root {
                @php
                    $color = '#eab308'; // Default yellow-500
                    if (auth()->check() && auth()->user()->barbershop) {
                        $color = auth()->user()->barbershop->primary_color;
                    } elseif (session()->has('tenant_color')) {
                        $color = session('tenant_color');
                    }
                    if($color == 'yellow-500') $color = '#eab308';
                @endphp
                --primary-main: {{ \App\Helpers\ColorHelper::hexToRgbString($color) }};
                --primary-light: {{ \App\Helpers\ColorHelper::adjustBrightness($color, 30) }};
                --primary-dark: {{ \App\Helpers\ColorHelper::adjustBrightness($color, -30) }};
                --primary-color: {{ $color }}; /* Mantenido para retrocompatibilidad con drop-shadow y text directo */
                --primary-glow: {{ $color }}4D;
            }
            .text-primary-dynamic { color: var(--primary-color); }
        </style>
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gray-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20 pointer-events-none"></div>
            
            <div class="relative z-10">
            <a href="{{ session('tenant_slug') ? route('tenant.landing', session('tenant_slug')) : '/' }}" class="flex flex-col items-center gap-2">
                    <svg class="h-16 w-auto" style="color: var(--primary-color); filter: drop-shadow(0 0 10px var(--primary-glow))" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"></path></svg>
                    <span class="text-3xl font-black text-white tracking-widest uppercase shadow-black drop-shadow-lg text-center">
                        @if(session('tenant_slug'))
                            {{-- La barbería del tenant --}}
                            @php
                                $tenantShop = \App\Models\Barbershop::where('slug', session('tenant_slug'))->first();
                            @endphp
                            {{ $tenantShop?->name ?? 'Barbería' }}
                        @else
                            Barber <span style="color: var(--primary-color)" class="transform inline-block scale-110">System</span>
                        @endif
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-gray-900 border border-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] ring-4 ring-gray-900 overflow-hidden sm:rounded-[2.5rem] relative z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
