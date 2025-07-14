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
        Schema::table('propostas_curso', function (Blueprint $table) {
            $table->foreign(['autor_id'], 'fk_autor')->references(['id'])->on('usuarios')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['avaliador_id'], 'fk_avaliador')->references(['id'])->on('usuarios')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['decisor_final_id'], 'fk_decisor')->references(['id'])->on('usuarios')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propostas_curso', function (Blueprint $table) {
            $table->dropForeign('fk_autor');
            $table->dropForeign('fk_avaliador');
            $table->dropForeign('fk_decisor');
        });
    }
};
