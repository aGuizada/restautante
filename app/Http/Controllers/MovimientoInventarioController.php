<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class MovimientoInventarioController extends Controller
{
    public function index()
    {
        try {
            $movimientos = MovimientoInventario::with(['compra', 'venta', 'producto', 'almacen'])->get();
            return response()->json($movimientos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tipo_movimiento' => 'required|in:Entrada,Salida',
                'cantidad' => 'required|integer|min:1',
                'precio_unitario' => 'required|numeric|min:0',
                'producto_id' => 'required|exists:productos,id',
                'almacen_id' => 'required|exists:almacenes,id',
                'compra_id' => 'nullable|exists:compras,id',
                'venta_id' => 'nullable|exists:ventas,id',
                'descripcion' => 'nullable|string',
            ]);

            $movimiento = MovimientoInventario::create($validated);
            return response()->json($movimiento, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(MovimientoInventario $movimiento)
    {
        return $movimiento->load(['compra', 'venta', 'producto', 'almacen']);
    }

    public function movimientosPorProducto($producto_id)
    {
        return MovimientoInventario::where('producto_id', $producto_id)
            ->with(['compra', 'venta', 'almacen'])
            ->get();
    }
}
