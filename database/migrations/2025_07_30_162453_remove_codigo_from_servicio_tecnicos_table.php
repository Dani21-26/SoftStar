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
            $table->dropColumn('codigo');
        });
    }
    
    public function down(): void
    {
        Schema::table('servicio_tecnicos', function (Blueprint $table) {
            $table->string('codigo')->nullable(); // O como estaba antes
        });
    }
    
};
