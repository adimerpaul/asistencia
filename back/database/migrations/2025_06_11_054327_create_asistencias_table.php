<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asignacion_id');
            $table->unsignedBigInteger('estudiante_id');
            $table->foreign('asignacion_id')->references('id')->on('asignaciones');
            $table->foreign('estudiante_id')->references('id')->on('estudiantes');
            $table->string('asistencia')->default('Asistencia'); // 'Asistencia', 'Atraso', 'Licenica', 'Falta'
            $table->date('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
