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
            $table->text('domingo')->change();
            $table->text('segunda')->change();
            $table->text('terca')->change();
            $table->text('quarta')->change();
            $table->text('quinta')->change();
            $table->text('sexta')->change();
            $table->text('sabado')->change();
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
