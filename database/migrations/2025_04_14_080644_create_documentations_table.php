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
        Schema::create('documentations', function (Blueprint $table) {
            $table->integer('ID_Doc')->primary();
            $table->integer('ID_Dev');
            $table->integer('ID_Ticket');
            $table->string('Judul');
            $table->string('Category');
            $table->string('Desc');
            $table->string('Text');
            $table->binary('Attc')->nullable();
        
            $table->foreign('ID_Dev')->references('ID_Account')->on('account')->onDelete('cascade');
            $table->foreign('ID_Ticket')->references('ID_Ticket')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};
