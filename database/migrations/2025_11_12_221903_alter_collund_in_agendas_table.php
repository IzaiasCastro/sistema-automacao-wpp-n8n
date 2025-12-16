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
            $table->integer('segunda_limite_agendamento')->nullable()->change();
            $table->integer('terca_limite_agendamento')->nullable()->change();
            $table->integer('quarta_limite_agendamento')->nullable()->change();
            $table->integer('quinta_limite_agendamento')->nullable()->change();
            $table->integer('sexta_limite_agendamento')->nullable()->change();
            $table->integer('sabado_limite_agendamento')->nullable()->change();
            $table->integer('domingo_limite_agendamento')->nullable()->change();
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
