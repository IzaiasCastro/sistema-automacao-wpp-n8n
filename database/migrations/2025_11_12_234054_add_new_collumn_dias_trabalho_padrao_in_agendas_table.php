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
            $table->json('dias_trabalho')->nullable();
            // inicio_expediente_padrao
            // fim_expediente_padrao
            $table->time('inicio_expediente_padrao')->nullable();
            $table->time('fim_expediente_padrao')->nullable();
            $table->time('inicio_intervalo_padrao')->nullable();
            $table->time('fim_intervalo_padrao')->nullable();
            $table->json('excecoes_horarios')->nullable();
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
