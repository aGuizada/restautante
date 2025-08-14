<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        return Inventario::with(['producto', 'almacen'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'almacen_id' => 'required|exists:almacenes,id',
            'cantidad' => 'required|integer|min:0',
            'punto_minimo' => 'required|integer|min:0',
        ]);

        return Inventario::create($validated);
    }

    public function show(Inventario $inventario)
    {
        return $inventario->load(['producto', 'almacen']);
    }

    public function update(Request $request, Inventario $inventario)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'almacen_id' => 'required|exists:almacenes,id',
            'cantidad' => 'required|integer|min:0',
            'punto_minimo' => 'required|integer|min:0',
        ]);

        $inventario->update($validated);
        return $inventario->load(['producto', 'almacen']);
    }

    public function destroy(Inventario $inventario)
    {
        $inventario->delete();
        return response()->noContent();
    }
}
