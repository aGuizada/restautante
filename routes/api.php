<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TipoServicioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\MovimientoCajaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DetalleCompraController;
use App\Http\Controllers\MovimientoInventarioController;

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Ruta para servir imágenes de productos
Route::get('/storage/productos/{filename}', function ($filename) {
    $path = storage_path('app/public/productos/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    $file = file_get_contents($path);
    $type = mime_content_type($path);
    
    return response($file, 200)->header('Content-Type', $type);
});

// Productos
Route::apiResource('productos', ProductoController::class);
Route::post('productos/upload', [ProductoController::class, 'uploadImage']);
  


// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Roles
    Route::apiResource('roles', RoleController::class);

    // Usuarios
    Route::apiResource('users', UserController::class);
    // Cajas
    Route::apiResource('cajas', CajaController::class);

    // Categorías
    Route::apiResource('categorias', CategoriaController::class);

  
    // Inventario
    Route::apiResource('inventario', InventarioController::class);

    // Clientes
    Route::apiResource('clientes', ClienteController::class);

    // Tipos de Servicio
    Route::apiResource('tipos-servicio', TipoServicioController::class);

    // Ventas
    Route::apiResource('ventas', VentaController::class);

    // Detalles de Ventas
    Route::apiResource('detalles-ventas', DetalleVentaController::class);

    // Movimientos de Caja
    Route::apiResource('movimientos-caja', MovimientoCajaController::class);

    // Proveedores
    Route::apiResource('proveedores', ProveedorController::class);

    // Almacenes
    Route::apiResource('almacenes', AlmacenController::class);

    // Compras
    Route::apiResource('compras', CompraController::class);

    // Detalles de Compra
    Route::apiResource('detalles-compra', DetalleCompraController::class);

    // Movimientos de Inventario
    Route::apiResource('movimientos-inventario', MovimientoInventarioController::class);

    // Productos con/ sin inventario por categoría
    Route::get('categorias/{categoria}/productos-con-inventario', [CategoriaController::class, 'productosConInventario']);
    Route::get('categorias/{categoria}/productos-sin-inventario', [CategoriaController::class, 'productosSinInventario']);

    // Productos por proveedor
    Route::get('proveedores/{proveedor}/productos', [ProveedorController::class, 'productos']);
    Route::get('proveedores/{proveedor}/productos-con-inventario', [ProveedorController::class, 'productosConInventario']);
});
