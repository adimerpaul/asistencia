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
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            // Llaves foráneas
            $table->foreignId('user_id')->constrained();
            $table->foreignId('curso_id')->constrained();
            $table->foreignId('docente_id')->constrained();

            // Otros campos
            $table->string('unidadEducativa');
            $table->string('taller')->nullable();
            $table->string('fases')->nullable();
            $table->string('docentesEncargados')->nullable();
            $table->string('anioFormacion');
            $table->string('gestion');

            $table->softDeletes(); // soporte para borrado lógico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
