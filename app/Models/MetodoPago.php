<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodoPago extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo'
    ];

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }
}
