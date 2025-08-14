<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TipoServicio;
use Illuminate\Http\Request;

class TipoServicioController extends Controller
{
    public function index()
    {
        return TipoServicio::with('ventas')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        return TipoServicio::create($validated);
    }

    public function show(TipoServicio $tipoServicio)
    {
        return $tipoServicio->load('ventas');
    }

    public function update(Request $request, TipoServicio $tipoServicio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        $tipoServicio->update($validated);
        return $tipoServicio->load('ventas');
    }

    public function destroy(TipoServicio $tipoServicio)
    {
        $tipoServicio->delete();
        return response()->noContent();
    }
}
