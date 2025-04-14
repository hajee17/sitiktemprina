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
        Schema::create('statuses', function (Blueprint $table) {
            $table->integer('ID_Status')->primary();
            $table->integer('ID_Ticket');
            $table->date('Update_Time');
            $table->string('Status');
            $table->string('Desc');
            $table->binary('Attc')->nullable();
        
            $table->foreign('ID_Ticket')->references('ID_Ticket')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
