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
        Schema::table('sessao_whatsapps', function (Blueprint $table) {
            //
            $table->dropColumn('token');
            $table->dropColumn(columns: 'secretkey');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessao_whatsapps', function (Blueprint $table) {
            //
        });
    }
};
