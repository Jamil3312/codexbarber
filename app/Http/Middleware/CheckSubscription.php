<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Superadmin: acceso total sin restricciones
        if ($user->is_superadmin) {
            return $next($request);
        }

        $barbershop = $user->barbershop;

        if (!$barbershop) {
            return $next($request);
        }

        // Bloqueo manual por el administrador
        if ($barbershop->subscription_status === 'blocked') {
            return response()->view('errors.suspended', [
                'reason'    => 'blocked',
                'message'   => $barbershop->block_reason ?? 'Tu cuenta ha sido bloqueada por el administrador.',
                'blockedAt' => $barbershop->blocked_at,
            ]);
        }

        // Suspensión por vencimiento de suscripción
        if ($barbershop->subscription_status === 'suspended') {
            return response()->view('errors.suspended', [
                'reason'  => 'suspended',
                'message' => null,
                'paidUntil' => $barbershop->paid_until,
            ]);
        }

        return $next($request);
    }
}
