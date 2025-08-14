<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Almacen;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index()
    {
        try {
            $compras = Compra::with(['proveedor', 'detalles.producto', 'detalles.almacen'])->get();
            return response()->json($compras);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'proveedor_id' => 'required|exists:proveedores,id',
                'numero_compra' => 'required|unique:compras',
                'subtotal' => 'required|numeric|min:0',
                'descuento' => 'numeric|min:0',
                'total' => 'required|numeric|min:0',
                'detalles' => 'required|array',
                'detalles.*.producto_id' => 'required|exists:productos,id',
                'detalles.*.almacen_id' => 'required|exists:almacenes,id',
                'detalles.*.cantidad' => 'required|integer|min:1',
                'detalles.*.precio_compra' => 'required|numeric|min:0',
                'detalles.*.precio_venta' => 'required|numeric|min:0',
                'detalles.*.fecha_vencimiento' => 'nullable|date',
            ]);

            $compra = Compra::create([
                'proveedor_id' => $validated['proveedor_id'],
                'numero_compra' => $validated['numero_compra'],
                'subtotal' => $validated['subtotal'],
                'descuento' => $validated['descuento'],
                'total' => $validated['total'],
            ]);

            foreach ($validated['detalles'] as $detalle) {
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $detalle['producto_id'],
                    'almacen_id' => $detalle['almacen_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_compra' => $detalle['precio_compra'],
                    'precio_venta' => $detalle['precio_venta'],
                    'fecha_vencimiento' => $detalle['fecha_vencimiento'],
                    'subtotal' => $detalle['cantidad'] * $detalle['precio_compra'],
                ]);
            }

            return response()->json($compra->load('detalles.producto', 'detalles.almacen'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Compra $compra)
    {
        return $compra->load('proveedor', 'detalles.producto', 'detalles.almacen');
    }
}
