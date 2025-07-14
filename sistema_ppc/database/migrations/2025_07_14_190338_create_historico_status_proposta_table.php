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
        Schema::create('historico_status_proposta', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('proposta_id')->index('proposta_id');
            $table->enum('status', ['submetida', 'em_avaliacao', 'ajustes_requeridos', 'em_aprovacao', 'aprovada', 'rejeitada']);
            $table->dateTime('data_status')->nullable()->useCurrent();
            $table->text('observacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_status_proposta');
    }
};
