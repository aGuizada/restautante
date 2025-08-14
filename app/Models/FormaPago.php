<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormaPago extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo'
    ];

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }
}
