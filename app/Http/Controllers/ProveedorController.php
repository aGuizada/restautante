<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        try {
            $proveedores = Proveedor::with('productos')->get();
            return response()->json($proveedores);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'correo_electronico' => 'nullable|email|max:255',
                'direccion' => 'nullable|string|max:255'
            ]);

            $proveedor = Proveedor::create($validated);
            return response()->json($proveedor->load('productos'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Proveedor $proveedor)
    {
        return $proveedor->load('productos');
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'correo_electronico' => 'nullable|email|max:255',
                'direccion' => 'nullable|string|max:255'
            ]);

            $proveedor->update($validated);
            return response()->json($proveedor->load('productos'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Proveedor $proveedor)
    {
        try {
            // Primero eliminamos los registros relacionados
            DB::transaction(function () use ($proveedor) {
                $proveedor->productos()->delete();
                $proveedor->compras()->delete();
                $proveedor->delete();
            });

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function productos(Proveedor $proveedor)
    {
        return $proveedor->productos()->with(['categoria'])->get();
    }

    public function productosConInventario(Proveedor $proveedor)
    {
        return $proveedor->productos()
            ->where('requiere_inventario', true)
            ->with(['categoria', 'inventario'])
            ->get();
    }

    public function compras(Proveedor $proveedor)
    {
        return $proveedor->compras()->with(['detallesCompra.producto'])->get();
    }
}
