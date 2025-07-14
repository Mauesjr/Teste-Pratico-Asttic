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
        Schema::create('propostas_curso', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nome');
            $table->integer('carga_horaria_total');
            $table->integer('quantidade_semestres');
            $table->text('justificativa');
            $table->text('impacto_social');
            $table->integer('autor_id')->nullable()->index('fk_autor');
            $table->integer('avaliador_id')->nullable()->index('fk_avaliador');
            $table->integer('decisor_final_id')->nullable()->index('fk_decisor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propostas_curso');
    }
};
