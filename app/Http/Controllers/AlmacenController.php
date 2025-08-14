<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function index()
    {
        try {
            $almacenes = Almacen::with('inventario.producto')->get();
            return response()->json($almacenes);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:255'
            ]);

            $almacen = Almacen::create($validated);
            return response()->json($almacen->load('inventario.producto'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Almacen $almacen)
    {
        return $almacen->load('inventario.producto');
    }

    public function update(Request $request, Almacen $almacen)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:255'
            ]);

            $almacen->update($validated);
            return response()->json($almacen->load('inventario.producto'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Almacen $almacen)
    {
        try {
            // Primero eliminamos los registros relacionados
            DB::transaction(function () use ($almacen) {
                $almacen->inventario()->delete();
                $almacen->detallesCompras()->delete();
                $almacen->movimientosInventario()->delete();
                $almacen->delete();
            });

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function stock(Almacen $almacen)
    {
        return $almacen->inventario()->with('producto')->get();
    }

    public function inventario(Almacen $almacen)
    {
        return $almacen->inventario()->with('producto')->get();
    }
}
