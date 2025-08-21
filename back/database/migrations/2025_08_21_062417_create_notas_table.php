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
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignacion_id')->constrained('asignaciones')->cascadeOnDelete();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();

            // Rangos: SER(0-10), SABER(0-35), HACER(0-35), DECIDIR(0-10) => TOTAL máx 90
            $table->unsignedTinyInteger('ser')->default(0);
            $table->unsignedTinyInteger('saber')->default(0);
            $table->unsignedTinyInteger('hacer')->default(0);
            $table->unsignedTinyInteger('decidir')->default(0);

            $table->unsignedSmallInteger('total')->default(0);
            $table->string('observacion')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['asignacion_id', 'estudiante_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
