<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_servicio');
            $table->string('cliente', 100);
            $table->unsignedBigInteger('tecnico_id');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->text('nota_servicio');
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_confirmacion')->useCurrent();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('tecnico_id')->references('id_empleado')->on('empleados');
            $table->foreign('producto_id')->references('id')->on('productos');

            // Índices
            $table->index('codigo_servicio');
            $table->index('tecnico_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_servicios');
    }
};