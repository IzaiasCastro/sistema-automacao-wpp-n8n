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
                'segunda_inicio_expediente', 'segunda_fim_expediente', 'segunda_inicio_almoco', 'segunda_fim_almoco',
                'terca_inicio_expediente', 'terca_fim_expediente', 'terca_inicio_almoco', 'terca_fim_almoco',
                'quarta_inicio_expediente', 'quarta_fim_expediente', 'quarta_inicio_almoco', 'quarta_fim_almoco',
                'quinta_inicio_expediente', 'quinta_fim_expediente', 'quinta_inicio_almoco', 'quinta_fim_almoco',
                'sexta_inicio_expediente', 'sexta_fim_expediente', 'sexta_inicio_almoco', 'sexta_fim_almoco',
                'sabado_inicio_expediente', 'sabado_fim_expediente', 'sabado_inicio_almoco', 'sabado_fim_almoco',
                'domingo_inicio_expediente', 'domingo_fim_expediente', 'domingo_inicio_almoco', 'domingo_fim_almoco',
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
