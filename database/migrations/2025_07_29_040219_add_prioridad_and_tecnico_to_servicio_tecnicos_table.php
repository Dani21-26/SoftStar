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
        Schema::table('servicio_tecnicos', function (Blueprint $table) {
            // Campo de prioridad con valores definidos
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])
                ->default('media')
                ->after('falla_reportada');

            // Clave foránea hacia la tabla users
            $table->foreignId('tecnico_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('prioridad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicio_tecnicos', function (Blueprint $table) {
    
            $table->dropForeign(['tecnico_id']);
            $table->dropColumn(['prioridad', 'tecnico_id']);
        });
    }
};
