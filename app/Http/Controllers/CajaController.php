<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        return Caja::with('ventas', 'movimientos')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_caja' => 'required|unique:cajas',
            'descripcion' => 'required|string',
            'estado' => 'required|in:Abierta,Cerrada',
            'monto_inicial' => 'required|numeric|min:0',
        ]);

        return Caja::create($validated);
    }

    public function show(Caja $caja)
    {
        return $caja->load('ventas', 'movimientos');
    }

    public function update(Request $request, Caja $caja)
    {
        $validated = $request->validate([
            'numero_caja' => 'required|unique:cajas,numero_caja,' . $caja->id,
            'descripcion' => 'required|string',
            'estado' => 'required|in:Abierta,Cerrada',
            'monto_inicial' => 'required|numeric|min:0',
        ]);

        $caja->update($validated);
        return $caja->load('ventas', 'movimientos');
    }

    public function destroy(Caja $caja)
    {
        $caja->delete();
        return response()->noContent();
    }
}
