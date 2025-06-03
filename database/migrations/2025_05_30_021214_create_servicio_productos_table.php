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
        Schema::create('servicio_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_servicio');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad')->default(1);
            
            $table->foreign('id_servicio')
                ->references('id_servicio')
                ->on('servicio_tecnicos')
                ->onDelete('cascade');
                
            $table->foreign('id_producto')
                ->references('id')
                ->on('productos')
                ->onDelete('restrict');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_productos');
    }
};
