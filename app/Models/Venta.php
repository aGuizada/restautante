<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'usuario_id',
        'caja_id',
        'tipo_servicio_id',
        'cliente_id',
        'metodo_pago_id',
        'subtotal',
        'descuento',
        'total',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function movimientoCaja()
    {
        return $this->hasOne(MovimientoCaja::class);
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
