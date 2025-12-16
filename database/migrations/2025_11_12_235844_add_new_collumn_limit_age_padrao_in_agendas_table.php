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
             $table->dropColumn([
                'limite_agendamentos',
                'segunda_limite_agendamento',
                'terca_limite_agendamento',
                'quarta_limite_agendamento',
                'quinta_limite_agendamento',
                'sexta_limite_agendamento',
                'sabado_limite_agendamento',
                'domingo_limite_agendamento',
            ]);
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
