<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable = ['numero_caja', 'descripcion', 'estado', 'monto_inicial'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }
}
