<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servicio_tecnicos', function (Blueprint $table) {
            $table->id('id');
            $table->string('codigo')->unique();
            $table->string('cliente');
            $table->string('router');
            $table->string('litebean');
            $table->text('direccion');
            $table->text('falla_reportada');
            $table->enum('estado', [ 'pendiente','confirmado', 'cancelado'])
            ->default('pendiente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicio_tecnicos');
    }
};