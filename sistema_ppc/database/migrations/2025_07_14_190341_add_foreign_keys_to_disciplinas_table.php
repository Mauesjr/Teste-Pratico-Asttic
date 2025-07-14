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
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->foreign(['curso_id'], 'disciplinas_ibfk_1')->references(['id'])->on('propostas_curso')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropForeign('disciplinas_ibfk_1');
        });
    }
};
