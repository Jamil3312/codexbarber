<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Barbershop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'primary_color', 'logo_url',
        'subscription_status', 'paid_until',
        'subscription_starts_at', 'subscription_type', 'grace_days',
        'block_reason', 'blocked_at', 'admin_notes', 'plan_type',
    ];

    protected $casts = [
        'paid_until'             => 'date',
        'subscription_starts_at' => 'date',
        'blocked_at'             => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function users()       { return $this->hasMany(User::class); }
    public function services()    { return $this->hasMany(Service::class); }
    public function appointments(){ return $this->hasMany(Appointment::class); }
    public function settings()    { return $this->hasMany(Setting::class); }
    public function blockedDays() { return $this->hasMany(BlockedDay::class); }

    // ── Computed Properties ────────────────────────────────────────

    /** Bloqueado manualmente por el admin (diferente a suspended por vencimiento) */
    public function getIsManuallyBlockedAttribute(): bool
    {
        return $this->subscription_status === 'blocked';
    }

    /** Está activo y dentro del período pagado (respetando días de gracia) */
    public function getIsActiveAttribute(): bool
    {
        if ($this->subscription_status !== 'active') return false;
        if (!$this->paid_until) return true; // Sin fecha = en prueba
        return Carbon::parse($this->paid_until)->addDays($this->grace_days ?? 3)->isFuture();
    }

    /** Dias restantes de la suscripción (negativo = vencida) */
    public function getDaysRemainingAttribute(): int
    {
        if (!$this->paid_until) return 999;
        return (int) Carbon::now()->diffInDays(Carbon::parse($this->paid_until), false);
    }
}
