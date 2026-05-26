<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_minutes',
        'barbershop_id'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
