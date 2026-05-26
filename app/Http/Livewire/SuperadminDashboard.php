<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Barbershop;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SuperadminDashboard extends Component
{
    // ── Create Barbershop Modal ────────────────────────────────────
    public bool $showCreateModal    = false;
    public string $newName          = '';
    public string $newSlug          = '';
    public string $newColor         = '#eab308';
    // Datos del socio (barbero)
    public string $ownerName        = '';
    public string $ownerEmail       = '';
    public string $ownerPassword    = '';
    // Credenciales generadas para mostrar al admin
    public bool   $showCredentials  = false;
    public array  $generatedCreds   = [];

    // ── Create User (Per-Seat) Modal ───────────────────────────────
    public bool $showCreateUserModal = false;
    public ?int $newUserBarbershopId = null;
    public string $newUserName = '';
    public string $newUserEmail = '';
    public string $newUserPassword = '';
    public string $newUserRole = 'employee'; // 'owner' | 'employee'

    // ── Subscription Modal ─────────────────────────────────────────
    public bool $showSubModal       = false;
    public ?int $subModalShopId     = null;
    public string $subStartDate     = '';
    public string $subType          = 'monthly'; // monthly | yearly
    public string $planType         = 'basic'; // basic | pro | elite
    public string $adminNotes       = '';

    // ── Block Modal ────────────────────────────────────────────────
    public bool $showBlockModal     = false;
    public ?int $blockModalShopId   = null;
    public string $blockReason      = '';

    // ── Edit Color Modal ───────────────────────────────────────────
    public bool $showEditColorModal = false;
    public ?int $editColorShopId    = null;
    public string $editColor        = '#eab308';

    // ──────────────────────────────────────────────────────────────
    // CREATE BARBERSHOP
    // ──────────────────────────────────────────────────────────────

    public function openCreateModal()
    {
        $this->reset(['newName', 'newSlug', 'newColor', 'ownerName', 'ownerEmail', 'ownerPassword', 'showCreateModal', 'showCredentials', 'generatedCreds']);
        $this->newColor = '#eab308';
        // Generar contraseña sugerida aleatoria
        $this->ownerPassword = \Str::random(10);
        $this->showCreateModal = true;
    }

    public function updatedNewName($value)
    {
        $this->newSlug = Str::slug($value);
    }

    public function createBarbershop()
    {
        $this->validate([
            'newName'       => 'required|string|min:3|max:100',
            'newSlug'       => 'required|string|max:60|unique:barbershops,slug',
            'newColor'      => 'required|string',
            'ownerName'     => 'required|string|min:2|max:100',
            'ownerEmail'    => 'required|email|unique:users,email',
            'ownerPassword' => 'required|string|min:8',
        ], [
            'newName.required'   => 'El nombre de la barbería es obligatorio.',
            'newSlug.unique'     => 'Ese identificador ya está en uso.',
            'ownerName.required' => 'El nombre del socio es obligatorio.',
            'ownerEmail.email'   => 'Ingresa un correo válido.',
            'ownerEmail.unique'  => 'Ese correo ya está registrado en el sistema.',
            'ownerPassword.min'  => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        // 1. Crear la barbería
        $barbershop = Barbershop::create([
            'name'                => $this->newName,
            'slug'                => $this->newSlug,
            'primary_color'       => $this->newColor,
            'subscription_status' => 'active',
            'subscription_type'   => 'monthly',
            'plan_type'           => 'basic', // Por defecto "Street" (basic)
            'grace_days'          => 3,
        ]);

        // 2. Crear el usuario socio (barbero) vinculado a la barbería
        \App\Models\User::create([
            'name'          => $this->ownerName,
            'email'         => $this->ownerEmail,
            'password'      => \Hash::make($this->ownerPassword),
            'is_barber'     => true,
            'is_owner'      => true,
            'is_superadmin' => false,
            'barbershop_id' => $barbershop->id,
        ]);

        // 3. Crear horario por defecto (9am - 6pm) para evitar agenda vacía
        \App\Models\Setting::create([
            'barbershop_id' => $barbershop->id,
            'start_time_1' => '09:00:00',
            'end_time_1' => '13:00:00',
            'start_time_2' => '14:00:00',
            'end_time_2' => '18:00:00',
            'slot_duration' => 30,
            'cancellation_notice' => 2,
        ]);

        // 4. Crear un servicio predeterminado demostrativo
        \App\Models\Service::create([
            'barbershop_id' => $barbershop->id,
            'name' => 'Corte Clásico',
            'duration_minutes' => 30,
            'price' => 35.00,
        ]);

        // 3. Guardar credenciales para mostrarlas
        $this->generatedCreds = [
            'barbershop' => $this->newName,
            'url'        => route('tenant.landing', $this->newSlug),
            'email'      => $this->ownerEmail,
            'password'   => $this->ownerPassword,
        ];

        $this->showCreateModal  = false;
        $this->showCredentials  = true;
        $this->reset(['newName', 'newSlug', 'newColor', 'ownerName', 'ownerEmail', 'ownerPassword']);
    }

    // ──────────────────────────────────────────────────────────────
    // CREATE USER (PER-SEAT)
    // ──────────────────────────────────────────────────────────────

    public function openCreateUserModal()
    {
        $this->reset(['newUserName', 'newUserEmail', 'newUserPassword', 'showCreateUserModal', 'showCredentials', 'generatedCreds', 'newUserRole', 'newUserBarbershopId']);
        $this->newUserPassword = Str::random(8);
        $this->showCreateUserModal = true;
    }

    public function createUser()
    {
        $this->validate([
            'newUserBarbershopId' => 'required|exists:barbershops,id',
            'newUserName'       => 'required|string|min:2|max:100',
            'newUserEmail'      => 'required|email|unique:users,email',
            'newUserPassword'   => 'required|string|min:6',
            'newUserRole'       => 'required|in:owner,employee',
        ]);

        $barbershop = Barbershop::find($this->newUserBarbershopId);

        \App\Models\User::create([
            'name'          => $this->newUserName,
            'email'         => $this->newUserEmail,
            'password'      => \Hash::make($this->newUserPassword),
            'is_barber'     => true,
            'is_owner'      => $this->newUserRole === 'owner',
            'is_superadmin' => false,
            'barbershop_id' => $this->newUserBarbershopId,
        ]);

        $this->showCreateUserModal = false;

        $this->generatedCreds = [
            'barbershop' => 'Licencia en: ' . $barbershop->name . ' (' . ($this->newUserRole === 'owner' ? 'Dueño/Independiente' : 'Empleado') . ')',
            'url'        => url('/login'),
            'email'      => $this->newUserEmail,
            'password'   => $this->newUserPassword,
        ];
        $this->showCredentials = true;
        session()->flash('message', '¡Licencia creada exitosamente!');
    }

    // ──────────────────────────────────────────────────────────────
    // SUBSCRIPTION MANAGEMENT
    // ──────────────────────────────────────────────────────────────

    public function openSubModal($id)
    {
        $shop = Barbershop::findOrFail($id);
        $this->subModalShopId = $id;
        $this->subStartDate   = $shop->subscription_starts_at
            ? $shop->subscription_starts_at->format('Y-m-d')
            : now()->format('Y-m-d');
        $this->subType        = $shop->subscription_type ?? 'monthly';
        $this->planType       = $shop->plan_type ?? 'basic';
        $this->adminNotes     = $shop->admin_notes ?? '';
        $this->showSubModal   = true;
    }

    public function saveSubscription()
    {
        $this->validate([
            'subStartDate' => 'required|date',
            'subType'      => 'required|in:monthly,yearly',
            'planType'     => 'required|in:basic,pro,elite',
        ]);

        $shop = Barbershop::findOrFail($this->subModalShopId);

        $starts   = Carbon::parse($this->subStartDate);
        $paidUntil = $this->subType === 'yearly'
            ? $starts->copy()->addYear()
            : $starts->copy()->addMonth();

        $shop->update([
            'subscription_starts_at' => $starts,
            'subscription_type'      => $this->subType,
            'plan_type'              => $this->planType,
            'paid_until'             => $paidUntil,
            'subscription_status'    => 'active',
            'admin_notes'            => $this->adminNotes ?: null,
        ]);

        $this->showSubModal = false;
        session()->flash('message', "✅ Suscripción de {$shop->name} configurada. Vence el {$paidUntil->translatedFormat('d \d\e F, Y')}.");
    }

    public function extendSubscription($id, $months)
    {
        $shop = Barbershop::findOrFail($id);

        $base = ($shop->paid_until && Carbon::parse($shop->paid_until)->isFuture())
            ? Carbon::parse($shop->paid_until)
            : now();

        $newExpiry = $base->addMonths($months);

        $shop->update([
            'paid_until'          => $newExpiry,
            'subscription_status' => 'active',
            // Limpiar bloqueo manual si se extiende
            'block_reason'        => null,
            'blocked_at'          => null,
        ]);

        $label = $months >= 12 ? '1 año' : "{$months} mes(es)";
        session()->flash('message', "✅ Suscripción de {$shop->name} extendida por {$label}. Nueva fecha: {$newExpiry->translatedFormat('d M, Y')}.");
    }

    // ──────────────────────────────────────────────────────────────
    // BLOCK / UNBLOCK
    // ──────────────────────────────────────────────────────────────

    public function openBlockModal($id)
    {
        $this->blockModalShopId = $id;
        $this->blockReason      = '';
        $this->showBlockModal   = true;
    }

    public function blockBarbershop()
    {
        $this->validate([
            'blockReason' => 'required|string|min:5|max:250',
        ], [
            'blockReason.required' => 'Debes indicar el motivo del bloqueo.',
            'blockReason.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $shop = Barbershop::findOrFail($this->blockModalShopId);
        $shop->update([
            'subscription_status' => 'blocked',
            'block_reason'        => $this->blockReason,
            'blocked_at'          => now(),
        ]);

        $this->showBlockModal = false;
        $this->blockReason    = '';
        session()->flash('error', "🔒 {$shop->name} ha sido bloqueada manualmente. Motivo: {$shop->block_reason}");
    }

    public function unblockBarbershop($id)
    {
        $shop = Barbershop::findOrFail($id);

        // Determinar el estado correcto tras desbloquear
        $newStatus = 'active';
        if ($shop->paid_until && Carbon::parse($shop->paid_until)->isPast()) {
            $graceFuture = Carbon::parse($shop->paid_until)->addDays($shop->grace_days ?? 3)->isFuture();
            $newStatus = $graceFuture ? 'active' : 'suspended';
        }

        $shop->update([
            'subscription_status' => $newStatus,
            'block_reason'        => null,
            'blocked_at'          => null,
        ]);

        $msg = $newStatus === 'suspended'
            ? "🔓 {$shop->name} desbloqueada — pero su suscripción ya venció. Considera renovarla."
            : "🔓 {$shop->name} desbloqueada y activa nuevamente.";

        session()->flash('message', $msg);
    }

    public function suspendBarbershop($id)
    {
        $shop = Barbershop::findOrFail($id);
        $shop->update([
            'subscription_status' => 'suspended',
            'block_reason'        => null,
            'blocked_at'          => null,
        ]);
        session()->flash('error', "⏸️ {$shop->name} ha sido suspendida.");
    }

    public function deleteBarbershop($id)
    {
        $shop = Barbershop::findOrFail($id);
        $name = $shop->name;

        // Eliminar todos los usuarios asociados a esta barbería de forma segura
        \App\Models\User::where('barbershop_id', $shop->id)->delete();

        // El resto de dependencias (citas, configuraciones, servicios) 
        // se eliminan automáticamente gracias al 'ON DELETE CASCADE' de la DB.
        $shop->delete();

        session()->flash('message', "🗑️ La barbería '{$name}' y todos sus datos han sido eliminados del sistema permanentemente.");
    }

    // ──────────────────────────────────────────────────────────────
    // EDIT COLOR
    // ──────────────────────────────────────────────────────────────

    public function openEditColorModal($id)
    {
        $shop = Barbershop::findOrFail($id);
        $this->editColorShopId = $id;
        $this->editColor       = $shop->primary_color ?? '#eab308';
        $this->showEditColorModal = true;
    }

    public function updateBarbershopColor()
    {
        $this->validate([
            'editColor' => 'required|string',
        ]);

        $shop = Barbershop::findOrFail($this->editColorShopId);
        $shop->update(['primary_color' => $this->editColor]);

        $this->showEditColorModal = false;
        session()->flash('message', "🎨 Color principal de {$shop->name} actualizado correctamente.");
    }

    // ──────────────────────────────────────────────────────────────
    // RENDER
    // ──────────────────────────────────────────────────────────────

    public function render()
    {
        // Auto-suspender las que vencieron (respetando días de gracia)
        Barbershop::where('subscription_status', 'active')
            ->whereNotNull('paid_until')
            ->whereRaw('DATE_ADD(paid_until, INTERVAL grace_days DAY) < NOW()')
            ->update(['subscription_status' => 'suspended']);

        $barbershops = Barbershop::withCount([
            'users as clients_count'      => fn($q) => $q->where('is_barber', false)->where('is_superadmin', false),
            'appointments as appointments_count',
            'appointments as monthly_appointments_count' => fn($q) => $q->whereMonth('date', now()->month)->whereYear('date', now()->year),
        ])->orderBy('name')->get();

        return view('livewire.superadmin-dashboard', [
            'barbershops' => $barbershops,
        ])->layout('layouts.app');
    }
}
