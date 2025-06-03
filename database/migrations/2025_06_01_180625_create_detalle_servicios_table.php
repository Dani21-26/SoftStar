<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalle_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicio_tecnicos')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('restrict');
            $table->text('nota');
            $table->json('productos_utilizados'); // Guardará {producto_id: cantidad}
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_servicios');
    }
};