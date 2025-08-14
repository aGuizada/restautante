<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return Categoria::with('productos')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        return Categoria::create($validated);
    }

    public function show(Categoria $categoria)
    {
        return $categoria->load(['productos' => function ($query) {
            $query->with(['stock', 'proveedor']);
        }]);
    }

    public function productosConInventario(Categoria $categoria)
    {
        return $categoria->productos()
            ->where('requiere_inventario', true)
            ->with(['stock', 'proveedor'])
            ->get();
    }

    public function productosSinInventario(Categoria $categoria)
    {
        return $categoria->productos()
            ->where('requiere_inventario', false)
            ->with(['proveedor'])
            ->get();
    }

    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        $categoria->update($validated);
        return $categoria->load('productos');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return response()->noContent();
    }
}
