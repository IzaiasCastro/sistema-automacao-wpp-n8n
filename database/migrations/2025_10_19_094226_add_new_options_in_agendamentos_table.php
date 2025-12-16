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
        Schema::table('agendamentos', function (Blueprint $table) {
            //adicionar novas opcoes enum na coluna status
            // $table->enum('status', ['confirmado', 'pendente', 'cancelado', 'remarcado', 'finalizado', 'ausente'])->default('confirmado')->change();
            //trocar enum por string
            $table->string('status')->default('confirmado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            //
        });
    }
};
