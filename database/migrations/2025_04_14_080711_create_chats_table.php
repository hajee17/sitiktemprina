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
        Schema::create('chats', function (Blueprint $table) {
            $table->integer('ID_Chat')->primary();
            $table->integer('ID_Account');
            $table->integer('ID_Ticket');
            $table->string('Chat');
        
            $table->foreign('ID_Account')->references('ID_Account')->on('account')->onDelete('cascade');
            $table->foreign('ID_Ticket')->references('ID_Ticket')->on('tickets')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
