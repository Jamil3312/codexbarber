<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($reason) && $reason === 'blocked' ? 'Cuenta Bloqueada' : 'Suscripción Suspendida' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-950 text-gray-100 font-sans flex items-center justify-center min-h-screen p-4">

    @php $isBlocked = isset($reason) && $reason === 'blocked'; @endphp

    <div class="max-w-md w-full bg-gray-900 border rounded-3xl p-8 text-center shadow-2xl relative overflow-hidden
                {{ $isBlocked ? 'border-orange-500/30' : 'border-red-500/30' }}">

        {{-- Barra superior de color --}}
        <div class="absolute top-0 inset-x-0 h-2 {{ $isBlocked ? 'bg-orange-500' : 'bg-red-500' }}"></div>

        {{-- Ícono --}}
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6
                    {{ $isBlocked ? 'bg-orange-500/10' : 'bg-red-500/10' }}">
            @if($isBlocked)
                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zM10 11V7a4 4 0 118 0v4"></path>
                </svg>
            @else
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            @endif
        </div>

        {{-- Título y mensaje --}}
        @if($isBlocked)
            <h1 class="text-2xl font-black text-white mb-2">Cuenta Bloqueada</h1>
            <p class="text-gray-400 mb-4 text-sm">Tu cuenta ha sido bloqueada manualmente por el administrador de Barber System.</p>

            @if(isset($message) && $message)
                <div class="bg-orange-500/10 border border-orange-500/20 rounded-2xl p-4 mb-6 text-sm text-orange-300 text-left">
                    <p class="text-[10px] text-orange-500 font-black uppercase tracking-widest mb-1">Motivo</p>
                    <p>{{ $message }}</p>
                </div>
            @endif

            @if(isset($blockedAt) && $blockedAt)
                <p class="text-[11px] text-gray-600 mb-6">Bloqueada el {{ \Carbon\Carbon::parse($blockedAt)->translatedFormat('d \d\e F, Y \a \l\a\s H:i') }}</p>
            @endif
        @else
            <h1 class="text-2xl font-black text-white mb-2">Suscripción Vencida</h1>
            <p class="text-gray-400 mb-4 text-sm">El período de servicio de tu barbería ha finalizado.</p>

            @if(isset($paidUntil) && $paidUntil)
                <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-4 mb-6 text-sm text-red-300 text-left">
                    <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mb-1">Venció el</p>
                    <p class="font-bold text-white">{{ \Carbon\Carbon::parse($paidUntil)->translatedFormat('d \d\e F, Y') }}</p>
                </div>
            @endif
        @endif

        {{-- CTA --}}
        <div class="bg-gray-800/60 rounded-2xl p-4 mb-6 text-sm text-gray-400">
            Comunícate con <strong class="text-yellow-500">Codex Solutions</strong> para
            {{ $isBlocked ? 'resolver la situación de tu cuenta.' : 'renovar tu suscripción.' }}
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-2xl transition-colors text-sm">
                Cerrar Sesión
            </button>
        </form>
    </div>
</body>
</html>
