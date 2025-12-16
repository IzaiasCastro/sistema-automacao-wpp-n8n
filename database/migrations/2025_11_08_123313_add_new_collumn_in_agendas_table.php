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
            $table->time('segunda_limite_agendamento')->nullable();
            //terca
            $table->time('terca_limite_agendamento')->nullable();
            //quarta
            $table->time('quarta_limite_agendamento')->nullable();
            //quinta
            $table->time('quinta_limite_agendamento')->nullable();
            //sexta
            $table->time('sexta_limite_agendamento')->nullable();
            //sabado
            $table->time('sabado_limite_agendamento')->nullable();
            //domingo
            $table->time('domingo_limite_agendamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn('segunda_limite_agendamento');
            $table->dropColumn('terca_limite_agendamento');
            $table->dropColumn('quarta_limite_agendamento');
            $table->dropColumn('quinta_limite_agendamento');
            $table->dropColumn('sexta_limite_agendamento');
            $table->dropColumn('sabado_limite_agendamento');
            $table->dropColumn('domingo_limite_agendamento');
        });
    }
};
