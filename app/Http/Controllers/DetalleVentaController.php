<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    public function index()
    {
        return DetalleVenta::with(['venta', 'producto', 'almacen'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'almacen_id' => 'required|exists:almacenes,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $detalle = DetalleVenta::create($validated);

        // Crear movimiento de inventario
        MovimientoInventario::create([
            'venta_id' => $validated['venta_id'],
            'producto_id' => $validated['producto_id'],
            'almacen_id' => $validated['almacen_id'],
            'tipo_movimiento' => 'Salida',
            'cantidad' => -$validated['cantidad'],
            'precio_unitario' => $validated['precio_unitario'],
            'descripcion' => 'Venta de producto',
        ]);

        return $detalle->load(['venta', 'producto', 'almacen']);
    }

    public function show(DetalleVenta $detalleVenta)
    {
        return $detalleVenta->load(['venta', 'producto', 'almacen']);
    }

    public function update(Request $request, DetalleVenta $detalleVenta)
    {
        $validated = $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'almacen_id' => 'required|exists:almacenes,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $detalleVenta->update($validated);

        // Actualizar movimiento de inventario
        MovimientoInventario::where('venta_id', $validated['venta_id'])
            ->where('producto_id', $validated['producto_id'])
            ->update([
                'cantidad' => -$validated['cantidad'],
                'precio_unitario' => $validated['precio_unitario'],
            ]);

        return $detalleVenta->load(['venta', 'producto', 'almacen']);
    }

    public function destroy(DetalleVenta $detalleVenta)
    {
        $detalleVenta->delete();
        return response()->noContent();
    }
}
