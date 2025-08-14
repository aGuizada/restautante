<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $fillable = ['caja_id', 'tipo_movimiento', 'monto', 'descripcion'];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
