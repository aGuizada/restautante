<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Almacen;
use Illuminate\Http\Request;

class DetalleCompraController extends Controller
{
    public function index()
    {
        return DetalleCompra::with(['compra', 'producto', 'almacen'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'compra_id' => 'required|exists:compras,id',
            'producto_id' => 'required|exists:productos,id',
            'almacen_id' => 'required|exists:almacenes,id',
            'cantidad' => 'required|integer|min:1',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $detalle = DetalleCompra::create($validated);

        // Crear movimiento de inventario
        MovimientoInventario::create([
            'compra_id' => $validated['compra_id'],
            'producto_id' => $validated['producto_id'],
            'almacen_id' => $validated['almacen_id'],
            'tipo_movimiento' => 'Entrada',
            'cantidad' => $validated['cantidad'],
            'precio_unitario' => $validated['precio_compra'],
            'descripcion' => 'Compra de producto',
        ]);

        return $detalle->load(['compra', 'producto', 'almacen']);
    }

    public function show(DetalleCompra $detalleCompra)
    {
        return $detalleCompra->load(['compra', 'producto', 'almacen']);
    }

    public function update(Request $request, DetalleCompra $detalleCompra)
    {
        $validated = $request->validate([
            'compra_id' => 'required|exists:compras,id',
            'producto_id' => 'required|exists:productos,id',
            'almacen_id' => 'required|exists:almacenes,id',
            'cantidad' => 'required|integer|min:1',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $detalleCompra->update($validated);
        return $detalleCompra->load(['compra', 'producto', 'almacen']);
    }

    public function destroy(DetalleCompra $detalleCompra)
    {
        $detalleCompra->delete();
        return response()->noContent();
    }
}
