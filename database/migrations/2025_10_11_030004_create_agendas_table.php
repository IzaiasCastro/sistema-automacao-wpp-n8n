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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            //organization relation
            $table->bigInteger('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade'); 
            $table->text('domingo')->nullable();
            $table->text('segunda')->nullable();
            $table->text('terca')->nullable();
            $table->text('quarta')->nullable();
            $table->text('quinta')->nullable();
            $table->text('sexta')->nullable();
            $table->text('sabado')->nullable();
            //chave estrangeira profissional id
            $table->foreignId('profissional_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
