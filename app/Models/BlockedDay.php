<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbershop_id',
        'date',
        'reason',
        'block_type',   // full | morning | afternoon
    ];

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
