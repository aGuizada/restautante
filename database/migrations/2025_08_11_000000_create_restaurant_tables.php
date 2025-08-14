<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tablas base sin relaciones
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_caja')->unique();
            $table->string('descripcion');
            $table->enum('estado', ['Abierta', 'Cerrada'])->default('Abierta');
            $table->decimal('monto_inicial', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono');
            $table->string('correo_electronico')->nullable();
            $table->string('direccion');
            $table->timestamps();
        });

        Schema::create('almacenes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->timestamps();
        });

        Schema::create('tipos_servicio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->timestamps();
        });

        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('descripcion');
            $table->timestamps();
        });

        // Insertar datos básicos
        // Roles
        DB::table('role')->insert([
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Usuario con acceso completo al sistema',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Vendedor',
                'descripcion' => 'Usuario encargado de realizar ventas',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Categorías
        DB::table('categorias')->insert([
            [
                'nombre' => 'Comidas',
                'descripcion' => 'Platos principales y acompañamientos',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Bebidas no alcohólicas y alcohólicas',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Bebidas Gaseosas',
                'descripcion' => 'Bebidas gaseosas y jugos',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Proveedores
        DB::table('proveedores')->insert([
            [
                'nombre' => 'Proveedor Principal',
                'telefono' => '987654321',
                'correo_electronico' => 'contacto@proveedor.com',
                'direccion' => 'Calle Principal 123',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Almacenes
        DB::table('almacenes')->insert([
            [
                'nombre' => 'Almacén Principal',
                'descripcion' => 'Almacén principal del restaurante',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Almacén Auxiliar',
                'descripcion' => 'Almacén auxiliar para productos de respaldo',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Tipos de servicio
        DB::table('tipos_servicio')->insert([
            [
                'nombre' => 'Mesa',
                'descripcion' => 'Servicio en mesa dentro del restaurante',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Para Llevar',
                'descripcion' => 'Servicio para llevar',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Métodos de pago
        DB::table('metodos_pago')->insert([
            [
                'nombre' => 'Efectivo',
                'descripcion' => 'Pago en efectivo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'QR',
                'descripcion' => 'Pago mediante código QR',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Tarjeta',
                'descripcion' => 'Pago con tarjeta de crédito/débito',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Transferencia',
                'descripcion' => 'Transferencia bancaria',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Tablas con relaciones
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_venta', 10, 2);
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('proveedor_id')->constrained('proveedores')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('imagen')->nullable();
            $table->boolean('requiere_inventario')->default(false);
            $table->timestamps();
        });

        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->string('numero_compra')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['Pendiente', 'Recibido', 'Anulado'])->default('Pendiente');
            $table->timestamps();
        });

        Schema::create('detalles_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras');
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('almacen_id')->constrained('almacenes');
            $table->integer('cantidad');
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('almacen_id')->constrained('almacenes');
            $table->integer('cantidad');
            $table->integer('punto_minimo');
            $table->timestamps();
        });

        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users');
            $table->foreignId('caja_id')->constrained('cajas');
            $table->foreignId('tipo_servicio_id')->constrained('tipos_servicio');
            $table->foreignId('cliente_id')->constrained('clientes')->nullable();
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['Pendiente', 'Pagado', 'Cancelado'])->default('Pendiente');
            $table->timestamps();
        });

        Schema::create('detalles_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->nullable();
            $table->foreignId('venta_id')->constrained('ventas')->nullable();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('almacen_id')->constrained('almacenes');
            $table->enum('tipo_movimiento', ['Entrada', 'Salida'])->default('Entrada');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->text('descripcion');
            $table->timestamps();
            $table->unique(['compra_id', 'venta_id']);
        });

        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas');
            $table->enum('tipo_movimiento', ['Apertura', 'Venta', 'Anulación', 'Reembolso']);
            $table->decimal('monto', 10, 2);
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalles_compra');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('proveedores');
        Schema::dropIfExists('movimientos_inventario');
        Schema::dropIfExists('detalles_ventas');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('movimientos_caja');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('cajas');
        Schema::dropIfExists('metodos_pago');
        Schema::dropIfExists('role');
        Schema::dropIfExists('tipos_servicio');
    }
};
