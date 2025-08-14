<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    protected $fillable = [
        'compra_id',
        'venta_id',
        'producto_id',
        'almacen_id',
        'tipo_movimiento',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'descripcion'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }
}
