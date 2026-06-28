<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_duration',
        'start_time_1',
        'end_time_1',
        'start_time_2',
        'end_time_2',
        'cancellation_notice',
        'barbershop_id',
        'buffer_time',
    ];

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
