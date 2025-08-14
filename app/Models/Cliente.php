<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'telefono', 'correo_electronico'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
