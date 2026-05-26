<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['barbershop_id', 'name', 'price', 'stock'];

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class);
    }
}
