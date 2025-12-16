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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            //organization relation
            $table->bigInteger('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->date('data');
            $table->time('horario');
            $table->string('nome');
            $table->string('telefone');
            $table->string('email');
            $table->string('servico');
            $table->string('observacao')->nullable();
            $table->foreignId('profissional_id')->constrained();
            $table->foreignId('servico_id')->constrained();
            $table->foreignId('agenda_id')->constrained();
            // $table->enum('status', ['pendente', 'confirmado', 'cancelado'])->default('pendente');
            // $table->enum('pagamento', ['pendente', 'pago'])->default('pendente');
            //trocar enum por string
            $table->string('pagamento')->default('pendente');
            $table->decimal('valor', 10, 2);
            $table->string('metodo_pagamento')->nullable();
            $table->foreignId('cliente_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
