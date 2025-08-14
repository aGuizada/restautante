<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        return Venta::with('usuario', 'caja', 'tipoServicio', 'cliente', 'detalles', 'movimientoCaja')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'caja_id' => 'required|exists:cajas,id',
            'tipo_servicio_id' => 'required|exists:tipos_servicio,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'subtotal' => 'required|numeric|min:0',
            'descuento' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:Efectivo,QR,Tarjeta',
            'estado' => 'required|in:Pendiente,Pagado,Cancelado',
            'detalles' => 'required|array',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.almacen_id' => 'required|exists:almacenes,id',
        ]);

        $venta = Venta::create($validated);

        // Crear detalles de venta
        foreach ($validated['detalles'] as $detalle) {
            $detalleVenta = $venta->detalles()->create([
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'subtotal' => $detalle['cantidad'] * $detalle['precio_unitario'],
            ]);

            // Crear movimiento de inventario
            MovimientoInventario::create([
                'venta_id' => $venta->id,
                'producto_id' => $detalle['producto_id'],
                'almacen_id' => $detalle['almacen_id'],
                'tipo_movimiento' => 'Salida',
                'cantidad' => -$detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'descripcion' => 'Venta de producto',
            ]);
        }

        return $venta->load('usuario', 'caja', 'tipoServicio', 'cliente', 'detalles', 'movimientoCaja');
    }

    public function show(Venta $venta)
    {
        return $venta->load([
            'usuario',
            'caja',
            'tipoServicio',
            'cliente',
            'detalles.producto',
            'detalles.almacen',
            'movimientoCaja'
        ]);
    }

    public function update(Request $request, Venta $venta)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'caja_id' => 'required|exists:cajas,id',
            'tipo_servicio_id' => 'required|exists:tipos_servicio,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'subtotal' => 'required|numeric|min:0',
            'descuento' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:Efectivo,QR,Tarjeta',
            'estado' => 'required|in:Pendiente,Pagado,Cancelado',
        ]);

        $venta->update($validated);
        return $venta->load('usuario', 'caja', 'tipoServicio', 'cliente', 'detalles', 'movimientoCaja');
    }

    public function destroy(Venta $venta)
    {
        $venta->delete();
        return response()->noContent();
    }
}
