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
        Schema::create('asignacion_estudiantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asignacion_id');
            $table->foreign('asignacion_id')->references('id')->on('asignaciones')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_estudiantes');
    }
};
