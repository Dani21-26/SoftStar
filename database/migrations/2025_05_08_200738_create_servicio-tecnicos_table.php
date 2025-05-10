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
        Schema::create('servicio-tecnicos', function (Blueprint $table) {
            $table->id(id_servicio);
            $table->string('nombreCliente');
            $table->unsignedBigInteger('id_Tecnico');
            $table->unsignedBigInteger('id_producto');
            $table->text('falla_reportada');
            $table->text('nota_servicio')->nullable()->comment('Detalle de lo realizado');
            $table->enum('estado', [
                'pendiente',
                'en_revision',
                'en_proceso',
                'completado',
                'cancelado'
            ])->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio-tecnicos');
    }
};
