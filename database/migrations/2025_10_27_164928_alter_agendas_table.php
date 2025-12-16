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
             $table->boolean('domingo')->default(true)->change();
            $table->boolean('segunda')->default(true)->change();
            $table->boolean('terca')->default(true)->change();
            $table->boolean('quarta')->default(true)->change();
            $table->boolean('quinta')->default(true)->change();
            $table->boolean('sexta')->default(true)->change();
            $table->boolean('sabado')->default(true)->change();
            $table->time('segunda_inicio_expediente')->nullable();
            $table->time('segunda_fim_expediente')->nullable();
            $table->time('segunda_inicio_almoco')->nullable();
            $table->time('segunda_fim_almoco')->nullable();
            $table->time('terca_inicio_expediente')->nullable();
            $table->time('terca_fim_expediente')->nullable();
            $table->time('terca_inicio_almoco')->nullable();
            $table->time('terca_fim_almoco')->nullable();
            $table->time('quarta_inicio_expediente')->nullable();
            $table->time('quarta_fim_expediente')->nullable();
            $table->time('quarta_inicio_almoco')->nullable();
            $table->time('quarta_fim_almoco')->nullable();
            $table->time('quinta_inicio_expediente')->nullable();
            $table->time('quinta_fim_expediente')->nullable();
            $table->time('quinta_inicio_almoco')->nullable();
            $table->time('quinta_fim_almoco')->nullable();
            $table->time('sexta_inicio_expediente')->nullable();
            $table->time('sexta_fim_expediente')->nullable();
            $table->time('sexta_inicio_almoco')->nullable();
            $table->time('sexta_fim_almoco')->nullable();
            $table->time('sabado_inicio_expediente')->nullable();
            $table->time('sabado_fim_expediente')->nullable();
            $table->time('sabado_inicio_almoco')->nullable();
            $table->time('sabado_fim_almoco')->nullable();
            $table->time('domingo_inicio_expediente')->nullable();
            $table->time('domingo_fim_expediente')->nullable();
            $table->time('domingo_inicio_almoco')->nullable();
            $table->time('domingo_fim_almoco')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
