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
            $table->string('codigo')->unique();
            $table->string('cliente');
            $table->text('direccion');
            $table->text('falla_reportada');
            $table->enum('estado', ['por_tomar', 'confirmado', 'cancelado'])->default('por_tomar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicio_tecnicos');
    }
};