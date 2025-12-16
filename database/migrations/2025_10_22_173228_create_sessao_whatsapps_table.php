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
        Schema::create('sessao_whatsapps', function (Blueprint $table) {
            $table->id();
            //organization
            $table->bigInteger('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade'); 
            $table->text('session_name');
            $table->text('secretkey');
            $table->text('webhook')->nullable();
            $table->boolean('active')->default(false)->nullable();
            $table->text('token')->nullable();
            $table->text('qrcode')->nullable();
            $table->string('phone_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessao_whatsapps');
    }
};
