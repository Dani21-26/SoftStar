<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_servicios', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->unsignedBigInteger('id_servicio');
            $table->unsignedBigInteger('id_empleado');
            $table->unsignedBigInteger('id_producto'); 
            $table->integer('cantidad')->default(1); 
            $table->text('productos_adicionales')->nullable(); 
            $table->text('diagnostico_real');
            $table->text('solucion_aplicada');
            $table->enum('estado', ['completado', 'cancelado', 'pendiente'])->default('pendiente');
            $table->timestamps();
            
            $table->foreign('id_servicio')->references('id_servicio')->on('servicio_tecnicos')->onDelete('cascade');
            $table->foreign('id_empleado')->references('id_empleado')->on('empleados');
            $table->foreign('id_producto')->references('id')->on('productos')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_servicios');
    }
};