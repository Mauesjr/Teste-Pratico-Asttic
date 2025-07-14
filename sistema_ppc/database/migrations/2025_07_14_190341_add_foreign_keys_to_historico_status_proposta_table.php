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
        Schema::table('historico_status_proposta', function (Blueprint $table) {
            $table->foreign(['proposta_id'], 'historico_status_proposta_ibfk_1')->references(['id'])->on('propostas_curso')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historico_status_proposta', function (Blueprint $table) {
            $table->dropForeign('historico_status_proposta_ibfk_1');
        });
    }
};
