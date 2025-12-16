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
            $table->time('tempo_medio')->nullable();
            $table->time('inicio_expediente')->nullable();
            $table->time('fim_expediente')->nullable();
            $table->time('inicio_almoco')->nullable();
            $table->time('fim_almoco')->nullable();
            //alterar colunas de dia da semana para boolean
            $table->boolean('segunda')->default(false)->change();
            $table->boolean('terca')->default(false)->change();
            $table->boolean('quarta')->default(false)->change();
            $table->boolean('quinta')->default(false)->change();
            $table->boolean('sexta')->default(false)->change();
            $table->boolean('sabado')->default(false)->change();
            $table->boolean('domingo')->default(false)->change();
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
