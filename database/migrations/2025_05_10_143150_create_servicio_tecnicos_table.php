<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servicio_tecnicos', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->string('codigo')->unique()->comment('Formato: ST-0001');
            $table->string('cliente', 100);
            $table->unsignedBigInteger('id_empleado');
            $table->foreign('id_empleado')->references('id_empleado')->on('empleados');
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->foreign('id_producto')->references('id')->on('productos');
            
            $table->text('falla_reportada');
            $table->enum('estado', ['pendiente', 'en_proceso'])->default('pendiente');
            $table->timestamps();
            
            
            $table->index('codigo');
            $table->index('cliente');
            $table->index('estado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicio_tecnicos');
    }
};