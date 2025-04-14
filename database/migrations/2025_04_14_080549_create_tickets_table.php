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
        Schema::create('tickets', function (Blueprint $table) {
            $table->integer('ID_Ticket')->primary();
            $table->string('SBU');
            $table->string('Dept');
            $table->string('Position');
            $table->string('Judul_Tiket');
            $table->string('Category');
            $table->string('Location');
            $table->string('Desc');
            $table->string('Attc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
