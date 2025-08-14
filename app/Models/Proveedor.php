<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable = [
        'nombre',
        'telefono',
        'correo_electronico',
        'direccion'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public function scopeConInventario($query)
    {
        return $query->has('productos.inventario');
    }
}
