<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return Cliente::with('ventas')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'telefono' => 'nullable|string',
            'correo_electronico' => 'nullable|string|email',
        ]);

        return Cliente::create($validated);
    }

    public function show(Cliente $cliente)
    {
        return $cliente->load('ventas');
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'telefono' => 'nullable|string',
            'correo_electronico' => 'nullable|string|email',
        ]);

        $cliente->update($validated);
        return $cliente->load('ventas');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->noContent();
    }
}
