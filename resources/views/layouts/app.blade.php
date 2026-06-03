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
            } elseif (auth()->check() && auth()->user()->barbershop) {
                $manifestUrl = route('tenant.manifest', ['slug' => auth()->user()->barbershop->slug]);
            }
        @endphp
        <link rel="manifest" href="{{ $manifestUrl }}">
        <meta name="theme-color" content="#eab308">
        @php
            $appleIconText = isset($barbershop) ? strtoupper(substr($barbershop->name, 0, 2)) : (auth()->check() && auth()->user()->barbershop ? strtoupper(substr(auth()->user()->barbershop->name, 0, 2)) : 'RD');
            $appleColorHex = isset($barbershop) && $barbershop->primary_color ? $barbershop->primary_color : (auth()->check() && auth()->user()->barbershop ? auth()->user()->barbershop->primary_color : '#eab308');
            if ($appleColorHex === 'yellow-500') $appleColorHex = '#eab308';
            $appleIconColor = str_replace('#', '%23', $appleColorHex);
        @endphp
        <link rel="apple-touch-icon" href="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><rect width='512' height='512' fill='%23030712'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-size='200' font-family='sans-serif' font-weight='bold' fill='{{ $appleIconColor }}'>{{ $appleIconText }}</text></svg>">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script>
            // ===== Service Worker & Notificaciones Push =====
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', async () => {
                    const registration = await navigator.serviceWorker.register("{{ asset('sw.js') }}");
                    window.__swRegistration = registration;
                });
            }

            // Solicitar permiso de notificaciones SOLO después de que el usuario instala la PWA
            window.addEventListener('appinstalled', (evt) => {
                console.log('PWA instalada exitosamente');
                if ('Notification' in window && Notification.permission === 'default') {
                    // Pequeño delay para dejar que el sistema operativo termine la instalación
                    setTimeout(() => {
                        Notification.requestPermission();
                    }, 1500);
                }
            });

            // ===== PWA Install Logic =====
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                // Prevent the mini-infobar from appearing on mobile
                e.preventDefault();
                // Stash the event so it can be triggered later.
                deferredPrompt = e;
                // Update UI notify the user they can install the PWA
                const installBtnDesktop = document.getElementById('installAppBtnDesktop');
                const installBtnMobile = document.getElementById('installAppBtnMobile');
                
                if(installBtnDesktop) installBtnDesktop.classList.remove('hidden');
                if(installBtnMobile) installBtnMobile.classList.remove('hidden');
            });

            function installPWA() {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the install prompt');
                            const installBtnDesktop = document.getElementById('installAppBtnDesktop');
                            const installBtnMobile = document.getElementById('installAppBtnMobile');
                            if(installBtnDesktop) installBtnDesktop.classList.add('hidden');
                            if(installBtnMobile) installBtnMobile.classList.add('hidden');
                        }
                        deferredPrompt = null;
                    });
                }
            }

            // ===== Vibración y Notificación Local al detectar nuevas alertas =====
            // Livewire puede disparar este evento desde el servidor
            document.addEventListener('livewire:load', () => {
                let lastCount = 0;

                // Polling cada 30 segundos para verificar nuevas notificaciones
                setInterval(async () => {
                    try {
                        const res = await fetch('/notifications/unread-count', {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (!res.ok) return;
                        const { count } = await res.json();

                        if (count > lastCount && lastCount !== null) {
                            // Vibrar (solo móvil - Android)
                            if ('vibrate' in navigator) {
                                navigator.vibrate([200, 100, 200, 100, 400]);
                            }

                            // Mostrar notificación nativa del sistema si está en background
                            if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
                                const reg = window.__swRegistration;
                                if (reg) {
                                    reg.showNotification('RD Barbería', {
                                        body: '🔔 Tienes ' + count + ' notificación(es) nueva(s).',
                                        icon: '/icons/icon-192.png',
                                        badge: '/icons/icon-72.png',
                                        vibrate: [200, 100, 200, 100, 400],
                                        tag: 'rdbarber-notif',
                                        renotify: true,
                                    });
                                }
                            }
                        }

                        lastCount = count;
                    } catch (e) {
                        // Silencioso en caso de error de red
                    }
                }, 30000);
            });
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
                --primary-color: {{ $color }};
                --primary-glow: {{ $color }}4D;
            }
            .text-primary-dynamic { color: var(--primary-color); }
            .bg-primary-dynamic { background-color: var(--primary-color); }
            .border-primary-dynamic { border-color: var(--primary-color); }
            .shadow-primary-dynamic { box-shadow: 0 10px 30px var(--primary-glow); }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-100 bg-gray-950">
        <div class="min-h-screen bg-gray-950 relative">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20 pointer-events-none"></div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-gray-900 shadow border-b border-gray-800">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @livewireScripts
    </body>
</html>
