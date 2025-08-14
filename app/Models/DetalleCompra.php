<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalles_compra';
    protected $fillable = ['compra_id', 'producto_id', 'almacen_id', 'cantidad', 'precio_compra', 'precio_venta', 'fecha_vencimiento', 'subtotal'];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
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
