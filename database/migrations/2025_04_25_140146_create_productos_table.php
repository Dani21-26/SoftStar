<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

    Schema::create('productos', function (Blueprint $table) {
        $table->id(); // Esto crea un campo 'id' autoincremental
        $table->string('nombre');
        $table->text('detalle')->nullable();
        $table->string('stock')->default('0 unidades');
        $table->unsignedBigInteger('id_categoria');
        $table->string('ubicacion');
        $table->decimal('precio', 10, 2);
        $table->unsignedBigInteger('id_proveedor');
        $table->timestamps(); // Esto crea created_at y updated_at automáticamente

    // Claves foráneas
        $table->foreign('id_categoria')->references('id_categoria')->on('categorias');
        $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores');
});
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
