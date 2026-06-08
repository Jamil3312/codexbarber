<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicSaaSController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// SaaS Global Landing
Route::get('/test-email', function (\Illuminate\Http\Request $request) {
    if (!$request->has('email')) {
        return "Por favor agrega tu correo a la URL, ejemplo: /test-email?email=tu-correo@gmail.com";
    }
    
    try {
        \Illuminate\Support\Facades\Mail::send('emails.appointment_reminder', [
            'barbershopName' => 'RD Barber Studio',
            'time' => '05:30 PM',
            'clientName' => 'Cristofer',
            'serviceName' => 'Corte Premium + Barba',
            'url' => url('/')
        ], function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Prueba de Correos CodexBarber');
        });
        return "¡Correo con diseño enviado con éxito a {$request->email}!";
    } catch (\Exception $e) {
        return "Hubo un error al enviar el correo. Revisa el archivo .env. Error exacto: " . $e->getMessage();
    }
});

Route::get('/', [PublicSaaSController::class, 'globalLanding'])->name('home');

// Acceso Profesional desde la landing global (limpia contexto de tenant)
Route::get('/acceso-profesional', function () {
    // Eliminar el contexto de tenant activo para mostrar el login global
    session()->forget(['tenant_barbershop_id', 'tenant_slug', 'tenant_color']);
    return redirect()->route('login');
})->name('global.login');

// Tenant Public Landing
Route::get('/b/{slug}', [PublicSaaSController::class, 'tenantLanding'])->name('tenant.landing');
Route::get('/b/{slug}/pwa-manifest', [PublicSaaSController::class, 'tenantManifest'])->name('tenant.manifest');

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Redirigir según rol para evitar que barbers/superadmin vean el panel de clientes
    if ($user->is_superadmin) {
        return redirect()->route('superadmin.dashboard');
    }

    if ($user->is_barber) {
        return redirect()->route('admin.dashboard');
    }

    // Cliente normal → panel de reservas
    return view('dashboard');
})->middleware(['auth', 'verified', 'subscribed'])->name('dashboard');

Route::middleware(['auth', 'subscribed'])->group(function () {
    Route::get('/superadmin/dashboard', \App\Http\Livewire\SuperadminDashboard::class)->name('superadmin.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas para el Barbero
    Route::get('/admin/dashboard', \App\Http\Livewire\AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/settings', \App\Http\Livewire\SettingsManager::class)->name('admin.settings');
    Route::get('/admin/services', \App\Http\Livewire\ServicesManager::class)->name('admin.services');
    Route::get('/admin/team', \App\Http\Livewire\TeamManager::class)->name('admin.team');
    Route::get('/admin/pos', \App\Http\Livewire\PosTerminal::class)->name('admin.pos');
    Route::get('/admin/commissions', \App\Http\Livewire\CommissionReport::class)->name('admin.commissions');

    // Endpoint JSON para polling de notificaciones (CSRF seguro dentro del grupo web)
    Route::get('/notifications/unread-count', function (\Illuminate\Http\Request $request) {
        return response()->json([
            'count' => $request->user()->unreadNotifications()->count(),
        ]);
    })->name('notifications.unread-count');
});

require __DIR__.'/auth.php';
