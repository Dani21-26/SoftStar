<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Cambiar el tipo de la columna stock de string a integer
            $table->integer('stock')->change();

            // Agregar el nuevo campo stock_unidad
            $table->string('stock_unidad')->after('stock')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Revertir stock a string
            $table->string('stock')->change();

            // Eliminar el campo stock_unidad
            $table->dropColumn('stock_unidad');
        });
    }
};
