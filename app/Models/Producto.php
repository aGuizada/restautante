<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\DetalleVenta;
use App\Models\DetalleCompra;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'precio_venta', 'categoria_id', 'proveedor_id', 'estado', 'imagen', 'requiere_inventario'];

    protected $appends = ['imagen_url'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function inventario()
    {
        return $this->hasMany(Inventario::class);
    }

    public function detallesCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function getImagenUrlAttribute()
    {
        if ($this->imagen && Storage::disk('public')->exists($this->imagen)) {
            // Extraer solo el nombre del archivo de la ruta completa
            $filename = basename($this->imagen);
            return url('api/storage/productos/' . $filename);
        }
        return null;
    }
}
