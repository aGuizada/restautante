<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacenes';
    protected $fillable = ['nombre', 'descripcion'];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'inventario');
    }

    public function inventario()
    {
        return $this->hasMany(Inventario::class);
    }

    public function detallesCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function movimientosInventario()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}
