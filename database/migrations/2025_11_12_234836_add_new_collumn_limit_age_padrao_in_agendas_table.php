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
        Schema::table('agendas', function (Blueprint $table) {
            $table->json('excecoes_horario')->nullable();
            //remover colunas
            $table->dropColumn(['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'inicio_expediente', 'fim_expediente', 'inicio_almoco', 'fim_almoco']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            //
        });
    }
};
