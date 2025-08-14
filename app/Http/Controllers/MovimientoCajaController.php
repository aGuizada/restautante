<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MovimientoCaja;
use Illuminate\Http\Request;

class MovimientoCajaController extends Controller
{
    public function index()
    {
        return MovimientoCaja::with('caja', 'venta')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'caja_id' => 'required|exists:cajas,id',
            'tipo_movimiento' => 'required|in:Apertura,Venta,Anulación,Reembolso',
            'monto' => 'required|numeric',
            'descripcion' => 'required|string',
        ]);

        return MovimientoCaja::create($validated);
    }

    public function show(MovimientoCaja $movimientoCaja)
    {
        return $movimientoCaja->load('caja', 'venta');
    }

    public function update(Request $request, MovimientoCaja $movimientoCaja)
    {
        $validated = $request->validate([
            'caja_id' => 'required|exists:cajas,id',
            'tipo_movimiento' => 'required|in:Apertura,Venta,Anulación,Reembolso',
            'monto' => 'required|numeric',
            'descripcion' => 'required|string',
        ]);

        $movimientoCaja->update($validated);
        return $movimientoCaja->load('caja', 'venta');
    }

    public function destroy(MovimientoCaja $movimientoCaja)
    {
        $movimientoCaja->delete();
        return response()->noContent();
    }
}
