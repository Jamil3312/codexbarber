<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'walkin_name',
        'date',
        'start_time',
        'end_time',
        'status',
        'reminder_sent',
        'price_paid',
        'barbershop_id',
        'barber_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
