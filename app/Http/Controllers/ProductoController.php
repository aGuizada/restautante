<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        try {
            $productos = Producto::with(['categoria'])->get();
            return response()->json($productos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'codigo' => 'required|unique:productos',
                'nombre' => 'required|string',
                'descripcion' => 'nullable|string',
                'precio_venta' => 'required|numeric|min:0',
                'categoria_id' => 'required|exists:categorias,id',
                'proveedor_id' => 'nullable|exists:proveedores,id',
                'estado' => 'required|in:Activo,Inactivo',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'requiere_inventario' => 'required|boolean',
            ]);

            // Si se sube una imagen
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $filename = 'productos/' . time() . '_' . $file->getClientOriginalName();
                Storage::disk('public')->put($filename, file_get_contents($file));
                $validated['imagen'] = $filename;
            }

            $producto = Producto::create($validated);
            return response()->json($producto->load(['categoria', 'proveedor', 'inventario', 'detallesCompras']), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Producto $producto)
    {
        return $producto->load(['categoria', 'proveedor', 'inventario', 'detallesCompras']);
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:productos,codigo,' . $producto->id,
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'precio_venta' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'estado' => 'required|in:Activo,Inactivo',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Si se sube una nueva imagen
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = 'productos/' . time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->put($filename, file_get_contents($file));
            $validated['imagen'] = $filename;

            // Si ya existía una imagen anterior, la eliminamos
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
        }

        $producto->update($validated);
        return $producto->load(['categoria', 'proveedor', 'inventario', 'detallesVentas']);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->noContent();
    }
}
