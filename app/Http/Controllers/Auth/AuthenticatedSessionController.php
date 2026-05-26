<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        // Si viene de una barbería específica (?from=slug), guardar el contexto
        // ANTES de regenerar la sesión para que persista
        $fromSlug = $request->get('from');
        if ($fromSlug) {
            $barbershop = \App\Models\Barbershop::where('slug', $fromSlug)->first();
            if ($barbershop) {
                session([
                    'tenant_slug'         => $barbershop->slug,
                    'tenant_barbershop_id' => $barbershop->id,
                    'tenant_color'        => $barbershop->primary_color,
                ]);
            }
        }

        $request->session()->regenerate();

        // Superadmin → siempre a su panel de control
        if ($user->is_superadmin) {
            return redirect()->route('superadmin.dashboard');
        }

        // Barbero/Socio → siempre a su panel administrativo
        if ($user->is_barber) {
            return redirect()->route('admin.dashboard');
        }

        // Cliente → al dashboard de reservas (con contexto de tenant preservado)
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $slug = null;
        $isSuperAdmin = false;

        if ($user) {
            $isSuperAdmin = (bool) $user->is_superadmin;

            // Para socios/clientes: obtener el slug de su barbería
            if (!$isSuperAdmin) {
                // Prioridad 1: sesión de tenancy activa
                $slug = session('tenant_slug');

                // Prioridad 2: barbería del usuario en BD
                if (!$slug) {
                    $barbershop = $user->barbershop; // Eager load seguro
                    $slug = $barbershop?->slug ?? null;
                }
            }
        }

        // Cerrar sesión y limpiar sesión completamente
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Superadmin → landing global
        if ($isSuperAdmin) {
            return redirect('/');
        }

        // Socio o cliente con barbería → landing del tenant
        if ($slug) {
            return redirect()->route('tenant.landing', ['slug' => $slug]);
        }

        // Fallback: landing global
        return redirect('/');
    }
}
