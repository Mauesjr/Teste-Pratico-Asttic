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
            $table->text('comentario_avaliador')->nullable()->after('impacto_social');
            $table->text('comentario_decisor')->nullable()->after('comentario_avaliador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('propostas_curso', function (Blueprint $table) {
            $table->dropColumn(['comentario_avaliador','comentario_decisor']);
        });
    }
};
